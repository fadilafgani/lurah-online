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

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 md:p-12">
                <form action="<?php echo route('pengaduan.simpan'); ?>" method="POST" enctype="multipart/form-data" x-data="{ isAnonim: false }">
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

                    <div class="mb-6" x-data="{ fileName: null }">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Foto Bukti <span class="text-rose-500">*</span></label>
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center cursor-pointer hover:border-blue-400 bg-gray-50/30 transition relative">
                            <input type="file" name="foto" required id="foto" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                @change="fileName = $event.target.files[0] ? $event.target.files[0].name : null">
                            
                            <div class="flex flex-col items-center justify-center space-y-2">
                                <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                <p class="text-sm text-slate-700 font-medium" x-text="fileName ? fileName : 'Klik untuk unggah foto'"></p>
                                <p class="text-xs text-gray-600">Klik untuk unggah foto</p>
                                <p class="text-xs text-gray-600">JPG, PNG • Maks 5MB</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50/40 border border-blue-100/60 rounded-xl p-5 mb-8">
                        <div class="flex items-center justify-between mb-4">
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
                                    <p class="text-xs text-gray-500">Identitas Anda tidak akan ditampilkan.</p>
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
                        <div x-show="!isAnonim" x-transition>
                            <input type="text" name="nama" placeholder="Nama Anda (Opsional)" 
                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 text-sm">
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3.5 bg-blue-950 text-white font-semibold rounded-xl bg-gradient-to-br from-[#0047AB] to-[#001D45] shadow-sm">
                        Kirim
                    </button>
                </form>
            </div>
        </main>
        </div>
        @include('components.footer')
    </body>
</html>
