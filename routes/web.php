<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/pengaduan', function () {
    return view('pengaduan');
})->name('pengaduan.buat');

Route::post('/pengaduan/simpan', function (Request $request) {
    $data = $request->validate([
        'judul' => 'required|string|max:255',
        'kategori' => 'required|string|max:255',
        'deskripsi' => 'required|string',
        'lokasi' => 'required|string',
        'foto' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        'nama' => 'nullable|string|max:255',
        'anonim' => 'nullable|boolean',
    ]);

    do {
        $kode = 'LO-' . now()->format('ymd') . '-' . strtoupper(Str::random(4));
    } while (\App\Models\Complaint::where('ticket_code', $kode)->exists());

    \App\Models\Complaint::create([
        'ticket_code' => $kode,
        'name' => $request->boolean('anonim') ? null : $data['nama'] ?? null,
        'title' => $data['judul'],
        'description' => $data['deskripsi'],
        'location' => $data['lokasi'],
        'category' => $data['kategori'],
        'photo' => $request->file('foto')->store('complaints', 'public'),
        'status' => 'submitted',
    ]);

    return redirect()->route('pengaduan.sukses', ['kode' => $kode]);
})->name('pengaduan.simpan');

Route::get('/pengaduan/sukses/{kode}', function (string $kode) {
    return view('pengaduan-sukses', compact('kode'));
})->name('pengaduan.sukses');

Route::get('/lacak', fn() => view('lacak'))->name('lacak');

Route::get('/lacak/cari', function (Request $request) {
    $request->validate(['kode' => 'required|string']);

    $tiket = \App\Models\Complaint::where('ticket_code', $request->kode)->first();

    if (!$tiket) {
        return view('lacak', ['notFound' => true]);
    }

    return view('lacak', compact('tiket'));
})->name('lacak.cari');

use App\Http\Controllers\LaporanController;

Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');

Route::get('/admin', fn() => view('login'))->name('admin.login');

Route::get('/admin/dashboard', fn() => view('admin.dashboard'))->name('admin.dashboard');

Route::get('/admin/lupa-kata-sandi', fn() => view('forgot-password'))->name('admin.forgot-password');

Route::get('/admin/reset-kata-sandi', fn() => view('reset-password'))->name('admin.reset-password');

Route::get('/admin/periksa-email', fn() => view('check-email'))->name('admin.check-email');
