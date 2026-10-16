<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Lurah Online</title>

        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="manifest" href="/manifest.json">

        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
        </style>
    </head>
    <body class="bg-white text-slate-900 antialiased">
        @include('components.navbar')
        <div class="relative w-full overflow-hidden">

            <div aria-hidden="true" class="pointer-events-none absolute inset-x-0 top-[271px] -z-10 flex justify-between blur-[350px]">
                <div class="-ml-24 h-[280px] w-[280px] shrink-0 rounded-full bg-[#0047AB]"></div>
                <div class="-mr-24 h-[220px] w-[460px] shrink-0 rounded-full bg-[#00B4D8] opacity-60"></div>
            </div>

        <main class="max-w-4xl mx-auto w-full px-4 pt-[126px] pb-12">

            <div class="text-center mb-8">
                <h1 class="text-3xl font-extrabold tracking-tight bg-gradient-to-r from-[#0047AB] to-[#153655] bg-clip-text text-transparent">Buat Pengaduan</h1>
                <p class="text-[#464646]" style="text-shadow: 0 4px 8px rgba(0,0,0,0.3);">Mulai buat pengaduan dan bantu kami meningkatkan pelayanan.</p>
            </div>

            <?php if(session('success')): ?>
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm">
                    <?php echo session('success'); ?>
                </div>
            <?php endif; ?>

            @if (!empty($errors) && ((is_object($errors) && method_exists($errors, 'any') && $errors->any()) || (is_array($errors) && count($errors) > 0)))
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm">
                    <p class="font-bold mb-1">Terdapat kesalahan pada formulir pengaduan:</p>
                    <ul class="list-disc list-inside space-y-0.5 text-xs">
                        @foreach ((is_object($errors) && method_exists($errors, 'all')) ? $errors->all() : (array) $errors as $error)
                            <li>{{ is_array($error) ? implode(', ', $error) : $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 md:p-12">
                {{-- Identitas Warga Terautentikasi --}}
                <div class="mb-8 p-4 rounded-2xl bg-gradient-to-r from-blue-50/80 to-indigo-50/60 border border-blue-200/70 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-[#0047AB] to-[#153655] text-white flex items-center justify-center font-extrabold text-base shadow-sm">
                            {{ strtoupper(substr($resident->name ?? 'W', 0, 1)) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-bold uppercase tracking-wider text-[#0047AB]">Pelapor Terverifikasi</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Aktif</span>
                            </div>
                            <p class="text-base font-extrabold text-[#153655] leading-tight">{{ $resident->name ?? '-' }}</p>
                            <p class="text-xs text-gray-500 font-mono">NIK: {{ $resident->nik ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="text-xs text-slate-500 flex sm:flex-col sm:items-end justify-between w-full sm:w-auto">
                        <span class="text-gray-400">Desa Banjarsari</span>
                        <span class="text-emerald-700 font-medium">Data diverifikasi dari database</span>
                    </div>
                </div>

                @php
                    $quota = $quota ?? (\App\Models\ComplaintSetting::checkQuota($resident->id ?? 0));
                @endphp

                {{-- Status Kuota Pengaduan Harian --}}
                @if($quota['is_active'])
                    <div class="mb-6 p-4 rounded-xl border {{ $quota['is_exceeded'] ? 'bg-amber-50/90 border-amber-300' : 'bg-blue-50/50 border-blue-200/60' }} flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <span class="p-2 rounded-lg {{ $quota['is_exceeded'] ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-[#0047AB]' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                            <div>
                                <p class="text-xs text-slate-500 font-semibold">Batas pengaduan harian: <span class="text-slate-800 font-bold">{{ $quota['max_limit'] }} pengaduan</span></p>
                                <p class="text-sm font-extrabold text-slate-800">
                                    Pengaduan hari ini: {{ $quota['used_today'] }} dari {{ $quota['max_limit'] }}
                                </p>
                            </div>
                        </div>
                        <div>
                            @if($quota['is_exceeded'])
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-200 text-amber-900">
                                    Batas Kuota Tercapai
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                    Sisa kuota: {{ $quota['remaining'] }}
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($quota['is_exceeded'])
                        <div class="mb-6 p-4 bg-amber-50 border border-amber-300 rounded-2xl text-amber-950 flex items-start gap-3">
                            <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div class="text-xs leading-relaxed">
                                <strong class="font-bold text-sm block mb-0.5">Anda telah mencapai batas pengaduan harian.</strong>
                                Setiap akun warga dibatasi maksimal {{ $quota['max_limit'] }} pengaduan per hari. Silakan mencoba kembali besok.
                            </div>
                        </div>
                    @endif
                @endif

                <form action="<?php echo route('pengaduan.simpan'); ?>" method="POST" enctype="multipart/form-data" x-data="complaintForm()" @submit="validateForm($event)">
                    <?php echo csrf_field(); ?>
                    
                    <div class="z-10 grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Judul Pengaduan <span class="text-rose-500">*</span></label>
                            <input type="text" name="judul" required placeholder="Contoh: Jalanan Rusak di Jl. Melati" 
                                class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:bg-white transition text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Kategori <span class="text-rose-500">*</span></label>
                            <select name="kategori" required 
                                class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:bg-white transition text-sm text-gray-500 appearance-none bg-[url('data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2024%2024%20fill%3D%22none%22%20stroke%3D%22currentColor%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E')] bg-[length:1.25rem] bg-[right_1rem_center] bg-no-repeat">
                                <option value="" disabled selected>Pilih kategori...</option>
                                <option value="Infrastruktur Jalan">Infrastruktur Jalan</option>
                                <option value="Kebersihan & Sampah">Kebersihan & Sampah</option>
                                <option value="Penerangan Jalan">Penerangan Jalan</option>
                                <option value="Saluran Air / Drainase">Saluran Air / Drainase</option>
                                <option value="Keamanan & Ketertiban">Keamanan & Ketertiban</option>
                                <option value="Administrasi Kependudukan">Administrasi Kependudukan</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi <span class="text-rose-500">*</span></label>
                        <textarea name="deskripsi" rows="5" required placeholder="Jelaskan kejadian secara rinci..." 
                            class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:bg-white transition text-sm resize-none"></textarea>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Lokasi <span class="text-rose-500">*</span></label>
                        <textarea name="lokasi" rows="3" required placeholder="RT 03/05 Jl. Melati No. 12" 
                            class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:bg-white transition text-sm resize-none"></textarea>
                    </div>

                    {{-- Komponen Unggah Foto: Kamera dan Galeri --}}
                    <div class="mb-6" id="foto-section">
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-semibold text-slate-700">
                                Foto Bukti Pengaduan <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-xs text-slate-600 font-medium">JPG, PNG, WEBP • Maks 5MB</span>
                        </div>

                        {{-- Pesan Validasi Frontend --}}
                        <div x-show="errorMessage" x-cloak class="mb-3.5 p-3.5 bg-rose-50 border border-rose-200 text-rose-700 text-xs rounded-xl flex items-start gap-2.5">
                            <svg class="w-4 h-4 shrink-0 text-rose-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div class="flex-1 font-medium">
                                <span class="font-bold">Perhatian:</span> <span x-text="errorMessage"></span>
                            </div>
                            <button type="button" @click="errorMessage = null" class="text-rose-400 hover:text-rose-700 text-base font-bold leading-none">&times;</button>
                        </div>

                        {{-- Input Form Asli (name="foto" untuk backend) --}}
                        <input type="file" name="foto" id="foto-input" class="hidden" accept="image/jpeg,image/png,image/jpg,image/webp">

                        {{-- Input Khusus Kamera (capture="environment" memprioritaskan kamera belakang mobile) --}}
                        <input type="file" x-ref="cameraInput" id="camera-input" accept="image/*" capture="environment" class="hidden" @change="handleFile($event, 'kamera')">

                        {{-- Input Khusus Galeri (File picker standar tanpa capture) --}}
                        <input type="file" x-ref="galleryInput" id="gallery-input" accept="image/*" class="hidden" @change="handleFile($event, 'galeri')">

                        {{-- Keadaan 1: Belum Ada Foto yang Dipilih --}}
                        <div x-show="!previewUrl" class="space-y-3">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                {{-- Tombol 1: Ambil Foto dari Kamera --}}
                                <button type="button" @click="triggerCamera()"
                                    class="group relative flex items-center p-4 bg-gradient-to-br from-blue-50/70 to-indigo-50/40 hover:from-blue-100/70 hover:to-indigo-100/50 border-2 border-dashed border-blue-200 hover:border-[#0047AB] rounded-2xl text-left transition-all duration-200 shadow-xs focus:outline-none focus:ring-2 focus:ring-blue-500/20 active:scale-[0.99] cursor-pointer">
                                    <div class="w-12 h-12 rounded-xl bg-white shadow-xs border border-blue-100 flex items-center justify-center text-2xl group-hover:scale-110 transition shrink-0 mr-3.5 text-[#0047AB]">
                                        📷
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-1.5">
                                            <h4 class="text-sm font-bold text-slate-800 group-hover:text-[#0047AB] transition">Ambil Foto dari Kamera</h4>
                                        </div>
                                        <p class="text-xs text-slate-500 leading-snug mt-0.5">Buka kamera perangkat untuk memotret langsung bukti di lokasi</p>
                                    </div>
                                </button>

                                {{-- Tombol 2: Pilih dari Galeri --}}
                                <button type="button" @click="triggerGallery()"
                                    class="group relative flex items-center p-4 bg-gradient-to-br from-slate-50/80 to-gray-50/50 hover:from-slate-100 hover:to-gray-100 border-2 border-dashed border-gray-300 hover:border-slate-400 rounded-2xl text-left transition-all duration-200 shadow-xs focus:outline-none focus:ring-2 focus:ring-slate-500/20 active:scale-[0.99] cursor-pointer">
                                    <div class="w-12 h-12 rounded-xl bg-white shadow-xs border border-gray-200 flex items-center justify-center text-2xl group-hover:scale-110 transition shrink-0 mr-3.5 text-slate-700">
                                        🖼️
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-1.5">
                                            <h4 class="text-sm font-bold text-slate-800 group-hover:text-slate-900 transition">Pilih dari Galeri</h4>
                                        </div>
                                        <p class="text-xs text-slate-500 leading-snug mt-0.5">Pilih foto dari album galeri perangkat atau file manager</p>
                                    </div>
                                </button>
                            </div>

                            <p class="text-[11px] text-gray-500 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Kamera diaktifkan setelah tombol dipilih. Jika kamera tidak tersedia atau izin ditolak, Anda dapat menggunakan opsi Galeri.
                            </p>
                        </div>

                        {{-- Keadaan 2: Foto Sudah Terpilih (Preview & Kontrol) --}}
                        <div x-show="previewUrl" x-cloak class="border border-slate-200 rounded-2xl p-4 bg-slate-50/50 transition">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                        ✓ Foto Bukti Terlampir
                                    </span>
                                    <span class="text-[11px] text-slate-500 font-medium" x-show="sourceType" x-text="'Sumber: ' + sourceType"></span>
                                </div>
                                <button type="button" @click="removePhoto()" class="text-xs font-semibold text-rose-600 hover:text-rose-700 hover:underline flex items-center gap-1 transition cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus Foto
                                </button>
                            </div>

                            {{-- Preview Gambar --}}
                            <div class="relative rounded-xl overflow-hidden bg-slate-900 border border-slate-200 max-h-72 flex items-center justify-center">
                                <img :src="previewUrl" alt="Preview Foto Bukti" class="max-h-72 w-full object-contain">
                            </div>

                            {{-- Informasi File & Opsi Penggantian Foto --}}
                            <div class="mt-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-3 border-t border-slate-200/80">
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-slate-800 truncate" x-text="fileName"></p>
                                    <p class="text-[11px] text-slate-500 font-medium" x-text="'Ukuran: ' + fileSize"></p>
                                </div>

                                <div class="flex items-center gap-2 shrink-0">
                                    <span class="text-xs text-slate-500 font-medium">Ganti foto:</span>
                                    <button type="button" @click="triggerCamera()"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white hover:bg-slate-100 border border-slate-300 rounded-lg text-xs font-bold text-slate-700 shadow-2xs transition active:scale-95 cursor-pointer"
                                        title="Ganti menggunakan kamera">
                                        📷 Kamera
                                    </button>
                                    <button type="button" @click="triggerGallery()"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white hover:bg-slate-100 border border-slate-300 rounded-lg text-xs font-bold text-slate-700 shadow-2xs transition active:scale-95 cursor-pointer"
                                        title="Ganti dari galeri perangkat">
                                        🖼️ Galeri
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50/40 border border-blue-100/60 rounded-xl p-5 mb-8">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <span class="p-2 bg-white border border-gray-100 shadow-xs rounded-lg text-blue-900">
                                    <svg width="24" height="24" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4,24 C12,10 36,10 44,24 C36,38 12,38 4,24 Z" fill="#1a56db" opacity="0.35"/>
                                        <circle cx="24" cy="24" r="9" fill="white" opacity="0.5"/>
                                        <circle cx="24" cy="24" r="6.5" fill="#1a3fa6" opacity="0.35"/>
                                        <circle cx="24" cy="24" r="3.5" fill="#0f1e5c" opacity="0.35"/>
                                        <line x1="8" y1="8" x2="40" y2="40" stroke="white" stroke-width="4" stroke-linecap="round"/>
                                        <line x1="8" y1="8" x2="40" y2="40" stroke="#1a3fa6" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                </span>
                                <div>
                                    <h4 class="text-sm font-semibold text-blue-950">Kirim sebagai Anonim?</h4>
                                    <p class="text-xs text-gray-500" x-show="!isAnonim">Nama Anda ({{ $resident->name ?? 'Warga' }}) akan ditampilkan.</p>
                                    <p class="text-xs text-blue-800 font-medium" x-show="isAnonim">Identitas disembunyikan di tampilan publik, namun tetap tercatat bagi petugas kelurahan.</p>
                                </div>
                            </div>
                            <button type="button" @click="isAnonim = !isAnonim"
                                :class="isAnonim ? 'bg-blue-900' : 'bg-gray-200'"
                                class="relative inline-flex h-8 w-16 shrink-0 cursor-pointer rounded-full border-2 border-gray-400 transition-colors duration-200 ease-in-out focus:outline-none">
                                <span :class="isAnonim ? 'translate-x-8' : 'translate-x-0'"
                                    class="pointer-events-none inline-block h-7 w-7 transform rounded-full bg-white shadow-md ring-1 ring-gray-300 transition duration-200 ease-in-out">
                                </span>
                            </button>
                            <input type="hidden" name="anonim" :value="isAnonim ? 1 : 0">
                        </div>
                    </div>

                    @if($quota['is_active'] && $quota['is_exceeded'])
                        <div class="space-y-2">
                            <button type="button" disabled
                                class="w-full py-3.5 bg-gray-200 text-gray-500 font-bold rounded-xl shadow-none cursor-not-allowed flex items-center justify-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                Anda Telah Mencapai Batas Pengaduan Harian (Coba Lagi Besok)
                            </button>
                            <p class="text-center text-xs text-gray-400">Pengiriman pengaduan ditutup sementara hingga pergantian hari (00.00 WIB).</p>
                        </div>
                    @else
                        <button type="submit" class="w-full py-3.5 bg-blue-950 text-white font-semibold rounded-xl bg-gradient-to-br from-[#0047AB] to-[#001D45] shadow-sm cursor-pointer hover:opacity-95 transition">
                            Kirim
                        </button>
                    @endif
                </form>
            </div>
        </main>
        </div>
        @include('components.footer')

        <script>
            function complaintForm() {
                return {
                    isAnonim: false,
                    previewUrl: null,
                    fileName: '',
                    fileSize: '',
                    sourceType: '',
                    errorMessage: null,

                    triggerCamera() {
                        this.errorMessage = null;
                        if (this.$refs.cameraInput) {
                            this.$refs.cameraInput.click();
                        }
                    },

                    triggerGallery() {
                        this.errorMessage = null;
                        if (this.$refs.galleryInput) {
                            this.$refs.galleryInput.click();
                        }
                    },

                    handleFile(event, source) {
                        const input = event.target;
                        if (!input.files || input.files.length === 0) {
                            return;
                        }

                        const file = input.files[0];

                        // 1. Validasi tipe file (hanya gambar)
                        const validExtensions = ['jpg', 'jpeg', 'png', 'webp'];
                        const fileExt = file.name.split('.').pop().toLowerCase();
                        const isImageMime = file.type.startsWith('image/');
                        const isValidExt = validExtensions.includes(fileExt);

                        if (!isImageMime && !isValidExt) {
                            this.errorMessage = 'Format file tidak didukung. Harap pilih foto bertipe JPG, JPEG, PNG, atau WEBP.';
                            input.value = '';
                            return;
                        }

                        // 2. Validasi batas ukuran file (maksimal 5MB)
                        const maxSize = 5 * 1024 * 1024;
                        if (file.size > maxSize) {
                            this.errorMessage = 'Ukuran foto melebihi batas maksimal 5MB (Ukuran file: ' + this.formatSize(file.size) + ').';
                            input.value = '';
                            return;
                        }

                        // 3. Update data & bersihkan resource URL lama
                        this.errorMessage = null;
                        this.sourceType = source === 'kamera' ? 'Kamera' : 'Galeri';
                        this.fileName = file.name;
                        this.fileSize = this.formatSize(file.size);

                        if (this.previewUrl) {
                            URL.revokeObjectURL(this.previewUrl);
                        }
                        this.previewUrl = URL.createObjectURL(file);

                        // 4. Sinkronkan ke input form utama (name="foto")
                        try {
                            const dt = new DataTransfer();
                            dt.items.add(file);
                            const mainInput = document.getElementById('foto-input');
                            if (mainInput) {
                                mainInput.files = dt.files;
                            }
                        } catch (e) {
                            console.error('DataTransfer tidak didukung oleh browser:', e);
                        }
                    },

                    removePhoto() {
                        if (this.previewUrl) {
                            URL.revokeObjectURL(this.previewUrl);
                            this.previewUrl = null;
                        }
                        this.fileName = '';
                        this.fileSize = '';
                        this.sourceType = '';
                        this.errorMessage = null;

                        const mainInput = document.getElementById('foto-input');
                        if (mainInput) mainInput.value = '';
                        if (this.$refs.cameraInput) this.$refs.cameraInput.value = '';
                        if (this.$refs.galleryInput) this.$refs.galleryInput.value = '';
                    },

                    formatSize(bytes) {
                        if (bytes === 0) return '0 B';
                        const k = 1024;
                        const sizes = ['B', 'KB', 'MB', 'GB'];
                        const i = Math.floor(Math.log(bytes) / Math.log(k));
                        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
                    },

                    validateForm(e) {
                        @if($quota['is_active'] && $quota['is_exceeded'])
                            e.preventDefault();
                            this.errorMessage = 'Anda telah mencapai batas pengaduan harian ({{ $quota['max_limit'] }} pengaduan). Silakan mencoba kembali besok.';
                            return false;
                        @endif

                        const mainInput = document.getElementById('foto-input');
                        if (!mainInput || !mainInput.files || mainInput.files.length === 0) {
                            e.preventDefault();
                            this.errorMessage = 'Foto bukti pengaduan wajib diunggah. Silakan pilih dari Kamera atau Galeri.';
                            const section = document.getElementById('foto-section');
                            if (section) {
                                section.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }
                            return false;
                        }
                        this.errorMessage = null;
                        return true;
                    }
                };
            }
        </script>
    </body>
</html>
