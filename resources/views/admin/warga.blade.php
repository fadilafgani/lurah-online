<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Warga – LurahOnline</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-white text-slate-900 antialiased">

@include('components.navbar-admin')

<main class="relative overflow-hidden pt-[126px] pb-16">

    {{-- Ambient background blobs --}}
    <div aria-hidden="true" class="pointer-events-none absolute inset-x-0 top-[271px] -z-10 flex justify-between blur-[350px]">
        <div class="-ml-24 h-[280px] w-[280px] shrink-0 rounded-full bg-[#0047AB]"></div>
        <div class="-mr-24 h-[220px] w-[460px] shrink-0 rounded-full bg-[#00B4D8] opacity-60"></div>
    </div>

    <div class="mx-auto flex w-full max-w-[1283px] flex-col items-center gap-[30px] px-6 sm:px-8 lg:px-4">

        {{-- Page Header --}}
        <div class="flex w-full flex-wrap items-center justify-between gap-5">
            <div class="flex items-center gap-3.5">
                <div class="flex h-[53px] w-[53px] shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[#0047AB] to-[#153655]">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <div class="flex flex-col gap-0.5">
                    <h1 class="bg-gradient-to-r from-[#0047AB] to-[#153655] bg-clip-text text-[30px] font-extrabold leading-none text-transparent">
                        Data Warga
                    </h1>
                    <p class="text-[17px] font-medium text-[#464646]">Kelola data kependudukan &amp; akun warga Desa Banjarsari</p>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.warga.template') }}" class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50 transition shadow-xs">
                    <svg class="w-4 h-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    <span>Download Template</span>
                </a>

                <button type="button" id="open-import-btn" class="flex items-center gap-2 rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-2.5 text-sm font-bold text-emerald-800 hover:bg-emerald-100 transition shadow-xs">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    <span>Import Excel</span>
                </button>

                <button type="button" id="open-tambah-btn" class="flex items-center gap-2 rounded-xl bg-gradient-to-r from-[#0047AB] to-[#153655] px-4 py-2.5 text-sm font-bold text-white hover:opacity-95 transition shadow-md shadow-blue-500/20">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    <span>Tambah Warga</span>
                </button>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid w-full grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-xs">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Warga</span>
                <p id="stat-total" class="text-3xl font-extrabold text-[#153655] mt-1">0</p>
                <span class="text-xs text-slate-400 mt-1 block">Terdaftar dalam database</span>
            </div>
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50/50 p-5 shadow-xs">
                <span class="text-xs font-semibold text-emerald-700 uppercase tracking-wider">Akun Aktif</span>
                <p id="stat-aktif" class="text-3xl font-extrabold text-emerald-700 mt-1">0</p>
                <span class="text-xs text-emerald-600 mt-1 block">Dapat login dan buat laporan</span>
            </div>
            <div class="rounded-2xl border border-rose-100 bg-rose-50/50 p-5 shadow-xs">
                <span class="text-xs font-semibold text-rose-700 uppercase tracking-wider">Akun Nonaktif</span>
                <p id="stat-nonaktif" class="text-3xl font-extrabold text-rose-700 mt-1">0</p>
                <span class="text-xs text-rose-600 mt-1 block">Akses pengaduan dibatasi</span>
            </div>
        </div>

        {{-- Filter & Search Bar --}}
        <div class="flex w-full flex-col sm:flex-row items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-xs">
            <div class="relative w-full sm:w-96">
                <div class="pointer-events-none absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                </div>
                <input type="text" id="warga-search" placeholder="Cari berdasarkan NIK atau nama..."
                    class="w-full rounded-xl border border-slate-200 bg-slate-50/50 py-2.5 pl-10 pr-4 text-sm outline-none focus:border-[#0047AB] focus:bg-white transition">
            </div>

            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                <label for="warga-filter-status" class="text-xs font-semibold text-slate-500 whitespace-nowrap">Filter Status:</label>
                <select id="warga-filter-status" class="rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-sm font-medium text-slate-700 outline-none focus:border-[#0047AB]">
                    <option value="all">Semua Status</option>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>
        </div>

        {{-- Table Data Warga --}}
        <div class="w-full overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/70 text-[13px] font-bold text-slate-600">
                            <th class="py-3.5 px-5">NIK</th>
                            <th class="py-3.5 px-5">Nama Lengkap</th>
                            <th class="py-3.5 px-5">RT/RW</th>
                            <th class="py-3.5 px-5">Alamat</th>
                            <th class="py-3.5 px-5 text-center">Status</th>
                            <th class="py-3.5 px-5 text-center">Pengaduan</th>
                            <th class="py-3.5 px-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="warga-table-body" class="divide-y divide-slate-100 text-sm">
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">Memuat data warga...</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Pagination footer --}}
            <div class="flex items-center justify-between border-t border-slate-100 px-5 py-3.5 text-xs text-slate-600">
                <span id="pagination-info">Menampilkan 0 data</span>
                <div class="flex items-center gap-2">
                    <button type="button" id="prev-page-btn" disabled class="rounded-lg border border-slate-200 px-3 py-1.5 font-semibold text-slate-600 hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed transition">
                        Sebelumnya
                    </button>
                    <span id="current-page-label" class="font-bold text-[#0047AB]">1</span>
                    <button type="button" id="next-page-btn" disabled class="rounded-lg border border-slate-200 px-3 py-1.5 font-semibold text-slate-600 hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed transition">
                        Selanjutnya
                    </button>
                </div>
            </div>
        </div>

    </div>
</main>

{{-- ── MODAL: Tambah Data Warga ── --}}
<div id="modal-tambah" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
            <h3 class="text-lg font-bold text-[#153655]">Tambah Data Warga Baru</h3>
            <button type="button" class="close-modal text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
        </div>
        <form id="form-tambah-warga" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">NIK (Nomor Induk Kependudukan) <span class="text-rose-500">*</span></label>
                <input type="text" id="tambah-nik" required maxlength="20" placeholder="16 digit angka NIK"
                    class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-mono focus:border-[#0047AB] outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input type="text" id="tambah-nama" required placeholder="Nama sesuai KTP"
                    class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-[#0047AB] outline-none">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">RT</label>
                    <input type="text" id="tambah-rt" placeholder="001"
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-[#0047AB] outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">RW</label>
                    <input type="text" id="tambah-rw" placeholder="002"
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-[#0047AB] outline-none">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Alamat Domisili</label>
                <textarea id="tambah-alamat" rows="2" placeholder="Nama jalan, nomor rumah, kampung..."
                    class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-[#0047AB] outline-none resize-none"></textarea>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Status Akun</label>
                <select id="tambah-status" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-[#0047AB] outline-none">
                    <option value="aktif">Aktif (Dapat Login &amp; Mengadu)</option>
                    <option value="nonaktif">Nonaktif (Akses Dibatasi)</option>
                </select>
            </div>
            <div class="p-3 bg-blue-50 rounded-xl text-xs text-blue-900 leading-relaxed">
                <strong>Catatan Keamanan:</strong> Kata sandi awal warga otomatis diatur sama dengan NIK warga. Warga diwajibkan mengubah kata sandi saat pertama kali login.
            </div>
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                <button type="button" class="close-modal rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                <button type="submit" id="submit-tambah-btn" class="rounded-xl bg-[#0047AB] px-5 py-2.5 text-sm font-bold text-white hover:bg-blue-800 transition">Simpan Warga</button>
            </div>
        </form>
    </div>
</div>

{{-- ── MODAL: Edit Data Warga ── --}}
<div id="modal-edit" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
            <h3 class="text-lg font-bold text-[#153655]">Edit Data Warga</h3>
            <button type="button" class="close-modal text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
        </div>
        <form id="form-edit-warga" class="space-y-4">
            <input type="hidden" id="edit-id">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">NIK (Tidak dapat diubah)</label>
                <input type="text" id="edit-nik" disabled
                    class="w-full rounded-xl border border-slate-200 bg-slate-100 px-4 py-2.5 text-sm font-mono text-slate-500 cursor-not-allowed">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input type="text" id="edit-nama" required
                    class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-[#0047AB] outline-none">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">RT</label>
                    <input type="text" id="edit-rt"
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-[#0047AB] outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">RW</label>
                    <input type="text" id="edit-rw"
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-[#0047AB] outline-none">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Alamat Domisili</label>
                <textarea id="edit-alamat" rows="2"
                    class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-[#0047AB] outline-none resize-none"></textarea>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Status Akun</label>
                <select id="edit-status" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-[#0047AB] outline-none">
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>
            <p class="text-xs text-slate-500">
                Catatan: Perubahan data tidak akan mereset atau mengubah kata sandi warga.
            </p>
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                <button type="button" class="close-modal rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                <button type="submit" id="submit-edit-btn" class="rounded-xl bg-[#0047AB] px-5 py-2.5 text-sm font-bold text-white hover:bg-blue-800 transition">Perbarui</button>
            </div>
        </form>
    </div>
</div>

{{-- ── MODAL: Detail Warga ── --}}
<div id="modal-detail" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="w-full max-w-xl rounded-2xl bg-white p-6 shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
            <h3 class="text-lg font-bold text-[#153655]">Detail Warga &amp; Histori</h3>
            <button type="button" class="close-modal text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
        </div>
        <div id="detail-content" class="space-y-4">
            <div class="p-4 bg-slate-50 rounded-xl space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-500">NIK:</span>
                    <strong id="det-nik" class="font-mono text-[#0047AB]"></strong>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Nama Lengkap:</span>
                    <strong id="det-nama" class="text-slate-800"></strong>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">RT / RW:</span>
                    <span id="det-rtrw" class="text-slate-800"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Alamat:</span>
                    <span id="det-alamat" class="text-slate-800 text-right"></span>
                </div>
                <div class="flex justify-between items-center pt-2 border-t border-slate-200">
                    <span class="text-slate-500">Status Akun:</span>
                    <span id="det-status" class="px-2 py-0.5 rounded-full text-xs font-bold"></span>
                </div>
            </div>

            <div>
                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Histori Pengaduan Warga Ini</h4>
                <div id="det-complaints-list" class="max-h-48 overflow-y-auto divide-y divide-slate-100 border border-slate-200 rounded-xl">
                    <p class="p-4 text-center text-xs text-slate-400">Tidak ada riwayat pengaduan.</p>
                </div>
            </div>
        </div>
        <div class="flex justify-end pt-4 border-t border-slate-100 mt-4">
            <button type="button" class="close-modal rounded-xl bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200">Tutup</button>
        </div>
    </div>
</div>

{{-- ── MODAL: Import Excel ── --}}
<div id="modal-import" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="w-full max-w-3xl rounded-2xl bg-white p-6 shadow-2xl flex flex-col max-h-[90vh]">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
            <div>
                <h3 class="text-lg font-bold text-[#153655]">Import Data Warga dari File Excel / CSV</h3>
                <p class="text-xs text-slate-500">Unggah file spreadsheet untuk menambahkan banyak data warga sekaligus</p>
            </div>
            <button type="button" class="close-modal text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
        </div>

        {{-- Step 1: Upload File --}}
        <div id="import-step-upload" class="space-y-4">
            <div class="border-2 border-dashed border-slate-300 rounded-2xl p-8 text-center hover:border-[#0047AB] bg-slate-50/50 transition relative">
                <input type="file" id="import-file" accept=".xlsx,.csv" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                <div class="flex flex-col items-center justify-center space-y-2">
                    <svg class="w-10 h-10 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="12" y1="18" x2="12" y2="12"/>
                        <line x1="9" y1="15" x2="15" y2="15"/>
                    </svg>
                    <p id="import-file-name" class="text-sm font-semibold text-slate-700">Pilih file atau seret file .xlsx / .csv ke sini</p>
                    <p class="text-xs text-slate-400">Mendukung format .xlsx dan .csv (Maksimal 5MB)</p>
                </div>
            </div>

            <div class="rounded-xl bg-blue-50/70 p-3.5 text-xs text-blue-900 flex items-start gap-2.5">
                <svg class="w-4 h-4 text-[#0047AB] shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                <div class="leading-relaxed">
                    Pastikan susunan kolom mengikuti template resmi: <strong>NIK, Nama Lengkap, Alamat, RT, RW, Status</strong>.<br>
                    Data NIK ganda di dalam file atau yang sudah ada di database akan ditandai dan dilewati tanpa menimpa data yang ada.
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-3">
                <button type="button" class="close-modal rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                <button type="button" id="btn-do-preview" class="rounded-xl bg-[#0047AB] px-5 py-2.5 text-sm font-bold text-white hover:bg-blue-800 transition">Unggah &amp; Periksa Data</button>
            </div>
        </div>

        {{-- Step 2: Preview & Validation Results --}}
        <div id="import-step-preview" class="hidden flex-1 overflow-hidden flex flex-col space-y-4">
            {{-- Summary Badges --}}
            <div class="grid grid-cols-4 gap-3 text-center">
                <div class="p-3 bg-slate-100 rounded-xl">
                    <span class="text-[11px] text-slate-500 uppercase font-semibold">Total Dibaca</span>
                    <p id="prev-total" class="text-xl font-extrabold text-slate-800">0</p>
                </div>
                <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-200">
                    <span class="text-[11px] text-emerald-700 uppercase font-semibold">Valid (Siap Impor)</span>
                    <p id="prev-valid" class="text-xl font-extrabold text-emerald-700">0</p>
                </div>
                <div class="p-3 bg-amber-50 rounded-xl border border-amber-200">
                    <span class="text-[11px] text-amber-700 uppercase font-semibold">Duplikat (Dilewati)</span>
                    <p id="prev-duplicate" class="text-xl font-extrabold text-amber-700">0</p>
                </div>
                <div class="p-3 bg-rose-50 rounded-xl border border-rose-200">
                    <span class="text-[11px] text-rose-700 uppercase font-semibold">Tidak Valid (Gagal)</span>
                    <p id="prev-invalid" class="text-xl font-extrabold text-rose-700">0</p>
                </div>
            </div>

            {{-- Preview Table --}}
            <div class="flex-1 overflow-y-auto border border-slate-200 rounded-xl">
                <table class="w-full text-left text-xs border-collapse">
                    <thead class="bg-slate-50 sticky top-0 border-b border-slate-200">
                        <tr>
                            <th class="p-2.5">Baris</th>
                            <th class="p-2.5">NIK</th>
                            <th class="p-2.5">Nama</th>
                            <th class="p-2.5">Alamat / RT RW</th>
                            <th class="p-2.5 text-center">Status Data</th>
                            <th class="p-2.5">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody id="import-preview-body" class="divide-y divide-slate-100">
                    </tbody>
                </table>
            </div>

            <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                <button type="button" id="btn-back-upload" class="rounded-xl px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100">Ganti File</button>
                <div class="flex items-center gap-2">
                    <button type="button" class="close-modal rounded-xl px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                    <button type="button" id="btn-do-confirm" class="rounded-xl bg-emerald-600 px-5 py-2 text-sm font-bold text-white hover:bg-emerald-700 transition">Konfirmasi Import</button>
                </div>
            </div>
        </div>

        {{-- Step 3: Success Report --}}
        <div id="import-step-done" class="hidden space-y-4 text-center py-6">
            <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 mx-auto flex items-center justify-center">
                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <h4 class="text-xl font-bold text-slate-800">Proses Import Selesai</h4>
            <p id="import-done-msg" class="text-sm text-slate-600"></p>
            <div class="pt-4">
                <button type="button" class="close-modal rounded-xl bg-[#0047AB] px-6 py-2.5 text-sm font-bold text-white hover:bg-blue-800">Selesai &amp; Perbarui Tabel</button>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const token = localStorage.getItem('admin_token');
    let currentPage = 1;
    let searchTimeout = null;
    let importToken = null;

    function getAuthHeaders() {
        const headers = { 'Accept': 'application/json' };
        if (token) headers['Authorization'] = 'Bearer ' + token;
        return headers;
    }

    async function apiRequest(url, options = {}) {
        options.headers = { ...getAuthHeaders(), ...(options.headers || {}) };
        const response = await fetch(url, options);
        if (response.status === 401) {
            localStorage.removeItem('admin_token');
            window.location.href = "{{ route('admin.login') }}";
            throw new Error('Sesi berakhir, silakan login kembali.');
        }
        const data = await response.json();
        if (!response.ok) {
            throw new Error(data.message || 'Terjadi kesalahan sistem.');
        }
        return data;
    }

    // ── Notifikasi & Dialog Swal Seragam ──────────────────────
    function notify(type, message, title = '') {
        if (window.Swal) {
            Swal.fire({
                icon: type,
                title: title || (type === 'success' ? 'Berhasil!' : (type === 'error' ? 'Terjadi Kesalahan' : 'Pemberitahuan')),
                text: message,
                confirmButtonColor: type === 'error' ? '#D83D3D' : '#0047AB',
                timer: type === 'success' ? 3000 : undefined
            });
        } else {
            alert(message);
        }
    }

    async function confirmDialog(title, text, confirmBtnText = 'Ya, Lanjutkan') {
        if (window.Swal) {
            const result = await Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0047AB',
                cancelButtonColor: '#656565',
                confirmButtonText: confirmBtnText,
                cancelButtonText: 'Batal'
            });
            return result.isConfirmed;
        }
        return confirm(text);
    }

    // ── Load Warga Data ──────────────────────────────────────
    async function loadWarga(page = 1) {
        currentPage = page;
        const search = document.getElementById('warga-search').value.trim();
        const status = document.getElementById('warga-filter-status').value;
        const tbody = document.getElementById('warga-table-body');

        tbody.innerHTML = '<tr><td colspan="7" class="py-8 text-center text-slate-400">Memuat data warga...</td></tr>';

        try {
            const params = new URLSearchParams({ page: page, search: search, status: status });
            const res = await apiRequest('/api/admin/residents?' + params.toString());

            // Stats
            document.getElementById('stat-total').textContent = res.stats.total;
            document.getElementById('stat-aktif').textContent = res.stats.aktif;
            document.getElementById('stat-nonaktif').textContent = res.stats.nonaktif;

            // Pagination
            document.getElementById('pagination-info').textContent =
                `Menampilkan ${res.data.length} dari ${res.pagination.total} warga`;
            document.getElementById('current-page-label').textContent = `${res.pagination.current_page} / ${res.pagination.last_page || 1}`;
            document.getElementById('prev-page-btn').disabled = res.pagination.current_page <= 1;
            document.getElementById('next-page-btn').disabled = res.pagination.current_page >= res.pagination.last_page;

            if (res.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="py-8 text-center text-slate-400">Tidak ada data warga yang cocok.</td></tr>';
                return;
            }

            tbody.innerHTML = res.data.map(item => `
                <tr class="hover:bg-slate-50/80 transition" data-id="${item.id}">
                    <td class="py-3.5 px-5 font-mono font-bold text-[#0047AB] whitespace-nowrap">${item.nik}</td>
                    <td class="py-3.5 px-5 font-semibold text-slate-800">${escapeHtml(item.name)}</td>
                    <td class="py-3.5 px-5 text-slate-500 whitespace-nowrap">RT ${item.rt || '-'} / RW ${item.rw || '-'}</td>
                    <td class="py-3.5 px-5 text-slate-600 max-w-xs truncate">${escapeHtml(item.address || '-')}</td>
                    <td class="py-3.5 px-5 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold ${item.status === 'aktif' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600'}">
                            ${item.status === 'aktif' ? 'Aktif' : 'Nonaktif'}
                        </span>
                    </td>
                    <td class="py-3.5 px-5 text-center">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold bg-blue-50 text-blue-700">
                            ${item.complaints_count || 0}
                        </span>
                    </td>
                    <td class="py-3.5 px-5 text-right whitespace-nowrap space-x-1.5">
                        <button type="button" class="btn-detail text-slate-600 hover:text-blue-600 p-1 hover:bg-slate-100 rounded" title="Lihat Detail">
                            <svg class="w-4 h-4 inline" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        </button>
                        <button type="button" class="btn-edit text-slate-600 hover:text-amber-600 p-1 hover:bg-slate-100 rounded" title="Edit Data">
                            <svg class="w-4 h-4 inline" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </button>
                        <button type="button" class="btn-toggle text-slate-600 hover:text-rose-600 p-1 hover:bg-slate-100 rounded" title="${item.status === 'aktif' ? 'Nonaktifkan Akun' : 'Aktifkan Akun'}">
                            <svg class="w-4 h-4 inline" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="5" width="22" height="14" rx="7" ry="7"/><circle cx="${item.status === 'aktif' ? '16' : '8'}" cy="12" r="3"/></svg>
                        </button>
                        <button type="button" class="btn-reset-sandi text-slate-600 hover:text-purple-600 p-1 hover:bg-slate-100 rounded" title="Reset Kata Sandi ke NIK">
                            <svg class="w-4 h-4 inline" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                        </button>
                    </td>
                </tr>
            `).join('');
        } catch (err) {
            tbody.innerHTML = `<tr><td colspan="7" class="py-8 text-center text-rose-500 font-semibold">${escapeHtml(err.message)}</td></tr>`;
        }
    }

    function escapeHtml(text) {
        if (!text) return '';
        return text.toString()
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // ── Search & Filter ──────────────────────────────────────
    document.getElementById('warga-search').addEventListener('input', function () {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => loadWarga(1), 350);
    });

    document.getElementById('warga-filter-status').addEventListener('change', () => loadWarga(1));

    document.getElementById('prev-page-btn').addEventListener('click', () => {
        if (currentPage > 1) loadWarga(currentPage - 1);
    });
    document.getElementById('next-page-btn').addEventListener('click', () => {
        loadWarga(currentPage + 1);
    });

    // ── Modals Handling ──────────────────────────────────────
    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    document.querySelectorAll('.close-modal').forEach(btn => {
        btn.addEventListener('click', function () {
            this.closest('.fixed').classList.add('hidden');
        });
    });

    // ── Tambah Warga ─────────────────────────────────────────
    document.getElementById('open-tambah-btn').addEventListener('click', () => {
        document.getElementById('form-tambah-warga').reset();
        openModal('modal-tambah');
    });

    document.getElementById('form-tambah-warga').addEventListener('submit', async function (e) {
        e.preventDefault();
        const submitBtn = document.getElementById('submit-tambah-btn');
        submitBtn.disabled = true;

        const payload = {
            nik: document.getElementById('tambah-nik').value.trim(),
            name: document.getElementById('tambah-nama').value.trim(),
            rt: document.getElementById('tambah-rt').value.trim(),
            rw: document.getElementById('tambah-rw').value.trim(),
            address: document.getElementById('tambah-alamat').value.trim(),
            status: document.getElementById('tambah-status').value,
        };

        try {
            const res = await apiRequest('/api/admin/residents', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            notify('success', res.message, 'Berhasil Menambahkan Warga!');
            closeModal('modal-tambah');
            loadWarga(1);
        } catch (err) {
            notify('error', err.message);
        } finally {
            submitBtn.disabled = false;
        }
    });

    // ── Table Actions (Delegated) ────────────────────────────
    document.getElementById('warga-table-body').addEventListener('click', async function (e) {
        const row = e.target.closest('tr');
        if (!row) return;
        const id = row.dataset.id;
        if (!id) return;

        // Detail
        if (e.target.closest('.btn-detail')) {
            try {
                const res = await apiRequest(`/api/admin/residents/${id}`);
                const w = res.data;
                document.getElementById('det-nik').textContent = w.nik;
                document.getElementById('det-nama').textContent = w.name;
                document.getElementById('det-rtrw').textContent = `RT ${w.rt || '-'} / RW ${w.rw || '-'}`;
                document.getElementById('det-alamat').textContent = w.address || '-';
                document.getElementById('det-status').textContent = w.status === 'aktif' ? 'Aktif' : 'Nonaktif';
                document.getElementById('det-status').className = `px-2 py-0.5 rounded-full text-xs font-bold ${w.status === 'aktif' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800'}`;

                const list = document.getElementById('det-complaints-list');
                if (w.complaints && w.complaints.length > 0) {
                    list.innerHTML = w.complaints.map(c => `
                        <div class="p-3 text-xs flex justify-between items-center hover:bg-slate-50">
                            <div>
                                <span class="font-mono text-[#0047AB] font-bold">${c.ticket_code}</span>
                                <p class="text-slate-700 font-semibold mt-0.5">${escapeHtml(c.title)}</p>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-100 text-slate-700">${c.status}</span>
                        </div>
                    `).join('');
                } else {
                    list.innerHTML = '<p class="p-4 text-center text-xs text-slate-400">Warga ini belum pernah mengajukan pengaduan.</p>';
                }

                openModal('modal-detail');
            } catch (err) {
                notify('error', err.message);
            }
            return;
        }

        // Edit
        if (e.target.closest('.btn-edit')) {
            try {
                const res = await apiRequest(`/api/admin/residents/${id}`);
                const w = res.data;
                document.getElementById('edit-id').value = w.id;
                document.getElementById('edit-nik').value = w.nik;
                document.getElementById('edit-nama').value = w.name;
                document.getElementById('edit-rt').value = w.rt !== '-' ? w.rt : '';
                document.getElementById('edit-rw').value = w.rw !== '-' ? w.rw : '';
                document.getElementById('edit-alamat').value = w.address !== '-' ? w.address : '';
                document.getElementById('edit-status').value = w.status;
                openModal('modal-edit');
            } catch (err) {
                notify('error', err.message);
            }
            return;
        }

        // Toggle Status
        if (e.target.closest('.btn-toggle')) {
            const confirmed = await confirmDialog('Ubah Status Warga?', 'Apakah Anda yakin ingin mengubah status aktif/nonaktif akun warga ini?');
            if (!confirmed) return;
            try {
                const res = await apiRequest(`/api/admin/residents/${id}/status`, { method: 'PUT' });
                notify('success', res.message);
                loadWarga(currentPage);
            } catch (err) {
                notify('error', err.message);
            }
            return;
        }

        // Reset Sandi
        if (e.target.closest('.btn-reset-sandi')) {
            const confirmed = await confirmDialog('Reset Kata Sandi?', 'Reset kata sandi warga ini kembali ke NIK? Warga akan diminta membuat kata sandi baru saat login berikutnya.');
            if (!confirmed) return;
            try {
                const res = await apiRequest(`/api/admin/residents/${id}/reset-password`, { method: 'POST' });
                notify('success', res.message, 'Kata Sandi Direset!');
            } catch (err) {
                notify('error', err.message);
            }
            return;
        }
    });

    // ── Edit Submit ──────────────────────────────────────────
    document.getElementById('form-edit-warga').addEventListener('submit', async function (e) {
        e.preventDefault();
        const id = document.getElementById('edit-id').value;
        const submitBtn = document.getElementById('submit-edit-btn');
        submitBtn.disabled = true;

        const payload = {
            name: document.getElementById('edit-nama').value.trim(),
            rt: document.getElementById('edit-rt').value.trim(),
            rw: document.getElementById('edit-rw').value.trim(),
            address: document.getElementById('edit-alamat').value.trim(),
            status: document.getElementById('edit-status').value,
        };

        try {
            const res = await apiRequest(`/api/admin/residents/${id}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            notify('success', res.message, 'Data Warga Diperbarui!');
            closeModal('modal-edit');
            loadWarga(currentPage);
        } catch (err) {
            notify('error', err.message);
        } finally {
            submitBtn.disabled = false;
        }
    });

    // ── Import Excel Flow ────────────────────────────────────
    const modalImport = document.getElementById('modal-import');
    const stepUpload = document.getElementById('import-step-upload');
    const stepPreview = document.getElementById('import-step-preview');
    const stepDone = document.getElementById('import-step-done');
    const importFileInput = document.getElementById('import-file');
    const importFileName = document.getElementById('import-file-name');

    document.getElementById('open-import-btn').addEventListener('click', () => {
        stepUpload.classList.remove('hidden');
        stepPreview.classList.add('hidden');
        stepDone.classList.add('hidden');
        importFileInput.value = '';
        importFileName.textContent = 'Pilih file atau seret file .xlsx / .csv ke sini';
        importToken = null;
        openModal('modal-import');
    });

    importFileInput.addEventListener('change', () => {
        if (importFileInput.files[0]) {
            importFileName.textContent = importFileInput.files[0].name;
        }
    });

    document.getElementById('btn-do-preview').addEventListener('click', async function () {
        if (!importFileInput.files[0]) {
            notify('warning', 'Harap pilih file terlebih dahulu.', 'Pilih File');
            return;
        }

        const btn = this;
        btn.disabled = true;
        btn.textContent = 'Memeriksa file...';

        const formData = new FormData();
        formData.append('file', importFileInput.files[0]);

        try {
            const res = await apiRequest('/api/admin/residents/preview-import', {
                method: 'POST',
                body: formData
            });

            importToken = res.token;

            // Isi Summary
            document.getElementById('prev-total').textContent = res.summary.total;
            document.getElementById('prev-valid').textContent = res.summary.valid;
            document.getElementById('prev-duplicate').textContent = res.summary.duplicate;
            document.getElementById('prev-invalid').textContent = res.summary.invalid;

            // Render Rows
            const tbody = document.getElementById('import-preview-body');
            tbody.innerHTML = res.rows.map(r => {
                let badge = '<span class="px-2 py-0.5 rounded font-bold bg-emerald-100 text-emerald-800">Valid</span>';
                if (r.status_type === 'duplicate') badge = '<span class="px-2 py-0.5 rounded font-bold bg-amber-100 text-amber-800">Duplikat</span>';
                if (r.status_type === 'invalid') badge = '<span class="px-2 py-0.5 rounded font-bold bg-rose-100 text-rose-800">Tidak Valid</span>';

                return `
                    <tr class="hover:bg-slate-50">
                        <td class="p-2.5 font-mono text-slate-500">${r.row_number}</td>
                        <td class="p-2.5 font-mono font-bold text-slate-800">${escapeHtml(r.nik)}</td>
                        <td class="p-2.5 font-semibold text-slate-700">${escapeHtml(r.name)}</td>
                        <td class="p-2.5 text-slate-500">${escapeHtml(r.address || '-')} (RT ${r.rt||'-'}/RW ${r.rw||'-'})</td>
                        <td class="p-2.5 text-center">${badge}</td>
                        <td class="p-2.5 text-slate-500">${r.reasons && r.reasons.length ? escapeHtml(r.reasons.join(', ')) : '-'}</td>
                    </tr>
                `;
            }).join('');

            stepUpload.classList.add('hidden');
            stepPreview.classList.remove('hidden');
        } catch (err) {
            notify('error', err.message);
        } finally {
            btn.disabled = false;
            btn.textContent = 'Unggah & Periksa Data';
        }
    });

    document.getElementById('btn-back-upload').addEventListener('click', () => {
        stepPreview.classList.add('hidden');
        stepUpload.classList.remove('hidden');
    });

    document.getElementById('btn-do-confirm').addEventListener('click', async function () {
        if (!importToken) return;

        const btn = this;
        btn.disabled = true;
        btn.textContent = 'Menyimpan...';

        try {
            const res = await apiRequest('/api/admin/residents/confirm-import', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ token: importToken })
            });

            stepPreview.classList.add('hidden');
            stepDone.classList.remove('hidden');
            document.getElementById('import-done-msg').textContent =
                `${res.message} (${res.imported_count} berhasil disimpan, ${res.skipped_count} dilewati).`;

            notify('success', res.message, 'Import Berhasil!');
            loadWarga(1);
        } catch (err) {
            notify('error', err.message);
        } finally {
            btn.disabled = false;
            btn.textContent = 'Konfirmasi Import';
        }
    });

    // Inisialisasi awal
    loadWarga(1);
})();
</script>

@include('components.footer')

</body>
</html>
