<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resident;
use App\Services\ResidentSpreadsheetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ResidentController extends Controller
{
    protected ResidentSpreadsheetService $spreadsheetService;

    public function __construct(ResidentSpreadsheetService $spreadsheetService)
    {
        $this->spreadsheetService = $spreadsheetService;
    }

    /**
     * Tampilkan halaman manajemen data warga (Blade).
     */
    public function page()
    {
        return view('admin.warga');
    }

    /**
     * API: Daftar warga dengan pencarian dan filter status.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Resident::withCount('complaints')->latest();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('nik', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $residents = $query->paginate(10)->withQueryString();

        $stats = [
            'total' => Resident::count(),
            'aktif' => Resident::where('status', 'aktif')->count(),
            'nonaktif' => Resident::where('status', 'nonaktif')->count(),
        ];

        return response()->json([
            'data' => $residents->items(),
            'pagination' => [
                'current_page' => $residents->currentPage(),
                'last_page' => $residents->lastPage(),
                'per_page' => $residents->perPage(),
                'total' => $residents->total(),
            ],
            'stats' => $stats,
        ]);
    }

    /**
     * API: Tambah data warga secara manual.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nik' => [
                'required',
                'string',
                'regex:/^\d{8,20}$/',
                Rule::unique('residents', 'nik')
            ],
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'rt' => ['nullable', 'string', 'max:10'],
            'rw' => ['nullable', 'string', 'max:10'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.regex' => 'Format NIK harus berupa angka (8-20 digit).',
            'nik.unique' => 'NIK sudah terdaftar dalam sistem.',
            'name.required' => 'Nama lengkap wajib diisi.',
            'status.in' => 'Status harus aktif atau nonaktif.',
        ]);

        // ponytail: Kata sandi awal adalah NIK yang di-hash
        $resident = Resident::create([
            'nik' => $data['nik'],
            'name' => $data['name'],
            'address' => $data['address'] ?? null,
            'rt' => $data['rt'] ?? null,
            'rw' => $data['rw'] ?? null,
            'status' => $data['status'],
            'password' => Hash::make($data['nik']),
            'must_change_password' => true,
        ]);

        return response()->json([
            'message' => 'Data warga berhasil ditambahkan.',
            'data' => $resident->toDisplayArray(),
        ], 201);
    }

    /**
     * API: Tampilkan detail data warga dan histori pengaduannya.
     */
    public function show(Resident $resident): JsonResponse
    {
        $complaints = $resident->complaints()
            ->latest()
            ->take(10)
            ->get(['ticket_code', 'title', 'category', 'status', 'created_at']);

        return response()->json([
            'data' => [
                ...$resident->toDisplayArray(),
                'complaints' => $complaints,
            ]
        ]);
    }

    /**
     * API: Perbarui data warga (tanpa mengubah password).
     */
    public function update(Request $request, Resident $resident): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'rt' => ['nullable', 'string', 'max:10'],
            'rw' => ['nullable', 'string', 'max:10'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'status.in' => 'Status harus aktif atau nonaktif.',
        ]);

        $resident->update([
            'name' => $data['name'],
            'address' => $data['address'] ?? null,
            'rt' => $data['rt'] ?? null,
            'rw' => $data['rw'] ?? null,
            'status' => $data['status'],
        ]);

        return response()->json([
            'message' => 'Data warga berhasil diperbarui.',
            'data' => $resident->toDisplayArray(),
        ]);
    }

    /**
     * API: Aktifkan atau nonaktifkan akun warga.
     */
    public function toggleStatus(Resident $resident): JsonResponse
    {
        $newStatus = $resident->status === 'aktif' ? 'nonaktif' : 'aktif';
        $resident->update(['status' => $newStatus]);

        return response()->json([
            'message' => "Akun warga berhasil di-{$newStatus}kan.",
            'data' => $resident->toDisplayArray(),
        ]);
    }

    /**
     * API: Reset kata sandi warga ke NIK (dengan kewajiban ubah sandi).
     * Password baru tidak diekspos secara terbuka sesuai standar keamanan.
     */
    public function resetPassword(Resident $resident): JsonResponse
    {
        $resident->update([
            'password' => Hash::make($resident->nik),
            'must_change_password' => true,
        ]);

        return response()->json([
            'message' => "Kata sandi warga {$resident->name} berhasil direset ke NIK. Warga wajib mengganti kata sandi saat login berikutnya.",
            'data' => $resident->toDisplayArray(),
        ]);
    }

    /**
     * Unduh template file Excel/CSV resmi.
     */
    public function downloadTemplate(Request $request)
    {
        $format = $request->query('format', 'xlsx');

        if ($format === 'csv') {
            $csvHeader = "NIK,Nama Lengkap,Alamat,RT,RW,Status\n3205010101900001,Ahmad Hidayat,Jl. Kenanga No. 12,001,002,Aktif\n3205010202920002,Siti Rahmawati,Jl. Mawar No. 05,002,002,Aktif\n";
            return response($csvHeader, 200, [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="template-data-warga.csv"',
            ]);
        }

        $xlsxContent = $this->spreadsheetService->generateTemplateXlsxContent();

        return response($xlsxContent, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="template-data-warga.xlsx"',
        ]);
    }

    /**
     * API: Unggah file dan dapatkan preview validasi sebelum konfirmasi import.
     */
    public function previewImport(Request $request): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'max:5120'], // maks 5MB
        ], [
            'file.required' => 'Harap pilih file untuk diunggah.',
            'file.max' => 'Ukuran file tidak boleh lebih dari 5MB.',
        ]);

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();

        if (!in_array(strtolower($extension), ['xlsx', 'csv', 'txt'])) {
            return response()->json([
                'message' => 'Format file tidak didukung. Harap unggah file berekstensi .xlsx atau .csv',
            ], 422);
        }

        $tempPath = $file->getRealPath();

        try {
            $rawRows = $this->spreadsheetService->readSpreadsheet($tempPath, $extension);
            $previewResult = $this->spreadsheetService->previewData($rawRows);

            if (!$previewResult['success']) {
                return response()->json(['message' => $previewResult['message']], 422);
            }

            // Simpan preview sementara ke cache/session/storage agar bisa dikonfirmasi
            $token = Str::random(32);
            Storage::put("temp_imports/{$token}.json", json_encode($previewResult['rows']));

            return response()->json([
                'success' => true,
                'token' => $token,
                'summary' => $previewResult['summary'],
                'rows' => $previewResult['rows'],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Gagal membaca isi file: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * API: Konfirmasi import data valid ke database.
     */
    public function confirmImport(Request $request): JsonResponse
    {
        $request->validate([
            'token' => ['required', 'string'],
        ]);

        $filePath = "temp_imports/{$request->token}.json";

        if (!Storage::exists($filePath)) {
            return response()->json([
                'message' => 'Sesi import telah kedaluwarsa atau tidak ditemukan. Silakan unggah kembali file Anda.',
            ], 404);
        }

        $allRows = json_decode(Storage::get($filePath), true);
        Storage::delete($filePath);

        // Hanya baris dengan status_type === 'valid' yang diimpor
        $validRows = array_filter($allRows, fn($r) => ($r['status_type'] ?? '') === 'valid');

        if (empty($validRows)) {
            return response()->json([
                'message' => 'Tidak ada data valid yang dapat diimpor.',
                'imported_count' => 0,
                'skipped_count' => count($allRows),
                'failed_rows' => [],
            ]);
        }

        $result = $this->spreadsheetService->importValidRows($validRows);

        return response()->json([
            'message' => "Proses import selesai. Berhasil menambahkan {$result['imported_count']} warga.",
            'imported_count' => $result['imported_count'],
            'skipped_count' => $result['skipped_count'] + (count($allRows) - count($validRows)),
            'failed_rows' => $result['failed_rows'],
        ]);
    }
}
