<?php

use App\Exceptions\DailyComplaintLimitException;
use App\Http\Controllers\Admin\ComplaintController;
use App\Http\Controllers\Admin\ComplaintSettingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProhibitedWordController;
use App\Http\Controllers\Admin\ResidentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\LacakController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ResidentAuthController;
use App\Models\Complaint;
use App\Models\ComplaintSetting;
use App\Models\ProhibitedWord;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

Route::get('/', function () {
    return view('welcome');
});

// Autentikasi Warga Berbasis NIK
Route::get('/warga/login', [ResidentAuthController::class, 'showLogin'])->name('warga.login');
Route::post('/warga/login', [ResidentAuthController::class, 'login'])
    ->middleware('throttle:10,1')
    ->name('warga.login.submit');
Route::post('/warga/logout', [ResidentAuthController::class, 'logout'])->name('warga.logout');

Route::get('/warga/ganti-password', [ResidentAuthController::class, 'showChangePassword'])
    ->middleware('resident')
    ->name('warga.ganti-password');
Route::post('/warga/ganti-password', [ResidentAuthController::class, 'updatePassword'])
    ->middleware('resident')
    ->name('warga.ganti-password.submit');

Route::get('/pengaduan', function () {
    $resident = Auth::guard('resident')->user();
    abort_unless($resident instanceof Resident, 401);

    $quota = ComplaintSetting::checkQuota($resident->id);

    return view('pengaduan', compact('resident', 'quota'));
})->middleware('resident')->name('pengaduan.buat');

Route::post('/pengaduan/simpan', function (Request $request) {
    $resident = Auth::guard('resident')->user();
    abort_unless($resident instanceof Resident, 401);

    // 1. Validasi status warga aktif
    if (! $resident->isActive()) {
        return redirect()->route('warga.login')
            ->withErrors(['nik' => 'Akun warga berstatus nonaktif. Silakan hubungi kantor desa/kelurahan.']);
    }

    // 2. Transaksi atomik dan locking baris resident untuk mencegah race condition
    try {
        $kode = DB::transaction(function () use ($resident, $request) {
            // Lock baris akun warga untuk serialize request bersamaan dari akun yang sama
            Resident::where('id', $resident->id)->lockForUpdate()->first();

            $quota = ComplaintSetting::checkQuota($resident->id);
            if ($quota['is_exceeded']) {
                throw new DailyComplaintLimitException($quota['max_limit'], $quota['used_today']);
            }

            $data = $request->validate([
                'judul' => 'required|string|max:255',
                'kategori' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'lokasi' => 'required|string',
                'foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
                'anonim' => 'nullable|boolean',
            ], [
                'foto.required' => 'Foto bukti pengaduan wajib diunggah.',
                'foto.image' => 'File bukti harus berupa gambar.',
                'foto.mimes' => 'Format foto harus berupa JPG, JPEG, PNG, atau WEBP.',
                'foto.max' => 'Ukuran foto maksimal adalah 5MB.',
            ]);

            $kataTerlarang = ProhibitedWord::matchingWord([
                $data['judul'],
                $data['deskripsi'],
                $data['lokasi'],
                $data['kategori'],
            ]);

            if ($kataTerlarang !== null) {
                throw ValidationException::withMessages([
                    'judul' => 'Laporan belum dapat dikirim. Silakan perbaiki kata-kata dalam laporan agar lebih sopan, lalu coba kirim kembali.',
                ]);
            }

            do {
                $kodeTiket = 'LO-'.now('Asia/Jakarta')->format('ymd').'-'.strtoupper(Str::random(4));
            } while (Complaint::where('ticket_code', $kodeTiket)->exists());

            // ponytail: Identitas pelapor mutlak diambil dari session resident terautentikasi
            Complaint::create([
                'ticket_code' => $kodeTiket,
                'resident_id' => $resident->id,
                'name' => $resident->name,
                'is_anonymous' => $request->boolean('anonim'),
                'title' => $data['judul'],
                'description' => $data['deskripsi'],
                'location' => $data['lokasi'],
                'category' => $data['kategori'],
                'photo' => $request->file('foto')->store('complaints', 'public'),
                'status' => 'submitted',
            ]);

            return $kodeTiket;
        });
    } catch (DailyComplaintLimitException $e) {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => "Anda telah mencapai batas maksimal {$e->maxLimit} pengaduan untuk hari ini. Silakan mencoba kembali besok.",
                'max_limit' => $e->maxLimit,
                'used_today' => $e->usedToday,
            ], 422);
        }

        return redirect()->back()->withInput()->withErrors([
            'kuota' => "Anda telah mencapai batas maksimal {$e->maxLimit} pengaduan untuk hari ini. Silakan mencoba kembali besok.",
        ]);
    }

    return redirect()->route('pengaduan.sukses', ['kode' => $kode]);
})->middleware('resident')->name('pengaduan.simpan');

Route::get('/pengaduan/sukses/{kode}', function (string $kode) {
    return view('pengaduan-sukses', compact('kode'));
})->name('pengaduan.sukses');

Route::get('/lacak', fn () => view('lacak'))->name('lacak');

Route::get('/lacak/cari', function (Request $request) {
    $request->validate(['kode' => 'required|string']);

    $tiket = Complaint::where('ticket_code', $request->kode)->first();

    if (! $tiket) {
        return view('lacak', ['notFound' => true]);
    }

    return view('lacak', compact('tiket'));
})->name('lacak.cari');

Route::post('/lacak/{ticket_code}/nilai', [LacakController::class, 'nilai'])
    ->name('lacak.nilai');

Route::post('/lacak/{ticket_code}/buka-kembali', [LacakController::class, 'bukaKembali'])
    ->name('lacak.buka-kembali');

Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');

Route::get('/admin', function () {
    // 1. Kebijakan Satu Role Aktif: jika sesi warga sedang aktif, tolak dengan 403
    if (Auth::guard('resident')->check()) {
        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'Anda sedang masuk sebagai warga. Silakan logout terlebih dahulu untuk masuk ke area admin.',
            ], 403);
        }
        abort(403, 'Akses ditolak. Anda sedang masuk sebagai warga. Silakan logout terlebih dahulu untuk mengakses area admin.');
    }

    // 2. Jika admin sudah login, langsung ke dashboard
    if (Auth::guard('web')->check()) {
        return redirect()->route('admin.dashboard');
    }

    return view('login');
})->name('admin.login');

Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

Route::middleware('admin.auth')->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::get('/admin/api/notifications', function () {
        $items = Complaint::where('status', 'submitted')->latest()->take(5)->get();

        return response()->json([
            'count' => $items->count(),
            'items' => $items->map(fn ($i) => [
                'ticket_code' => $i->ticket_code,
                'title' => $i->title,
                'time' => optional($i->created_at)->diffForHumans() ?? '-',
            ]),
        ]);
    })->name('admin.api.notifications');

    Route::get('/admin/notifikasi', function (Request $request) {
        $statusFilter = $request->get('filter', 'all');
        $query = Complaint::latest();
        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }
        $notifikasiList = $query->paginate(10)->withQueryString();

        return view('admin.notifikasi', compact('notifikasiList', 'statusFilter'));
    })->name('admin.notifikasi');

    Route::get('/admin/laporan',
        [ComplaintController::class, 'index'])
        ->name('admin.laporan');

    Route::put('/admin/laporan/{complaint:ticket_code}/status',
        [ComplaintController::class, 'updateStatus'])
        ->name('admin.laporan.status');

    Route::put('/admin/laporan/{complaint:ticket_code}/catatan',
        [ComplaintController::class, 'saveHandling'])
        ->name('admin.laporan.catatan');

    Route::put('/admin/laporan/{complaint:ticket_code}/selesaikan',
        [ComplaintController::class, 'complete'])
        ->name('admin.laporan.selesaikan');

    Route::delete('/admin/laporan/{ticketCode}',
        [ComplaintController::class, 'destroy'])
        ->name('admin.laporan.destroy');

    Route::get('/admin/kata-terlarang', [ProhibitedWordController::class, 'index'])->name('admin.kata-terlarang');
    Route::post('/admin/kata-terlarang', [ProhibitedWordController::class, 'update'])->name('admin.kata-terlarang.update');

    Route::get('/admin/akun-unit', fn () => view('admin.akun-unit'))->name('admin.akun-unit');

    Route::get('/admin/warga', [ResidentController::class, 'page'])->name('admin.warga');
    Route::get('/admin/warga/template', [ResidentController::class, 'downloadTemplate'])->name('admin.warga.template');

    // Pengaturan Batas Pengaduan Harian Warga
    Route::post('/admin/pengaturan/batas-harian', [ComplaintSettingController::class, 'update'])
        ->name('admin.pengaturan.batas-harian');
});

Route::get('/admin/lupa-kata-sandi', fn () => view('forgot-password'))->name('admin.forgot-password');

Route::get('/admin/periksa-email', fn () => view('check-email'))->name('admin.check-email');

Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])
    ->name('password.request');

Route::post('/forgot-password', [ForgotPasswordController::class, 'send'])
    ->name('password.email');

Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])
    ->name('password.reset');

Route::post('/reset-password', [ResetPasswordController::class, 'store'])
    ->name('password.update');
