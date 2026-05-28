<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Lurah Online</title>

        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
        </style>
    </head>
    <body class="bg-white text-slate-900 antialiased">

        <header class="sticky top-0 z-50 border-b border-gray-100 bg-white/80 backdrop-blur-md">
            <div class="container mx-auto flex h-20 items-center justify-between px-6">
                
                <a href="{{ url('/')}}" class="flex items-center gap-3 group">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-[#0047AB] to-[#001D45]">
                        <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="w-8 h-8">
                    </div>
                    <span class="text-xl font-bold tracking-tight bg-gradient-to-r from-[#0047AB] to-[#001D45] bg-clip-text text-transparent">
                    LurahOnline
                    </span>
                </a>
            <nav class="hidden md:block">
                    <ul class="flex items-center gap-2 text-[#464646]">
                        <li>
                            <a href="{{ url('/')}}" class="px-4 py-2 text-sm font-medium hover:text-[#0047AB] transition-colors">
                                Beranda
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/pengaduan')}}" class="rounded-lg bg-slate-100 px-4 py-2 text-sm font-bold">
                                Buat Pengaduan
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/lacak')}}" class="px-4 py-2 text-sm font-medium hover:text-[#0047AB] transition-colors">
                                Lacak Tiket
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/laporan')}}" class="px-4 py-2 text-sm font-medium hover:text-[#0047AB] transition-colors">
                                Laporan Publik
                            </a>
                        </li>
                    </ul>
                </nav>

                <div class="hidden md:block">
                    <a href="{{ url('/admin')}}" class="rounded-lg border border-slate-300 px-6 py-2 text-sm font-semibold text-[#464646] hover:bg-slate-100 transition-all shadow-sm">
                        Admin
                    </a>
                </div>
            </div>
        </header>

    <main class="max-w-4xl mx-auto px-4 py-12 w-full">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-blue-950 mb-2">Buat Pengaduan</h1>
            <p class="text-gray-500">Mulai buat pengaduan dan bantu kami meningkatkan pelayanan.</p>
        </div>

        <?php if(session('success')): ?>
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm">
                <?php echo session('success'); ?>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 md:p-12">
            <form action="<?php echo route('pengaduan.simpan'); ?>" method="POST" enctype="multipart/form-data" x-data="{ isAnonim: false }">
                <?php echo csrf_field(); ?>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
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
                            <option value="Infrastruktur">Infrastruktur & Jalanan</option>
                            <option value="Kebersihan">Kebersihan & Sampah</option>
                            <option value="Keamanan">Keamanan Lingkungan</option>
                            <option value="Fasilitas Umum">Fasilitas Umum</option>
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
                            <p class="text-xs text-gray-400">JPG, PNG • Maks 5MB</p>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50/40 border border-blue-100/60 rounded-xl p-5 mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <span class="p-2 bg-white border border-gray-100 shadow-xs rounded-lg text-blue-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path>
                                </svg>
                            </span>
                            <div>
                                <h4 class="text-sm font-semibold text-blue-950">Kirim sebagai Anonim?</h4>
                                <p class="text-xs text-gray-500">Identitas Anda tidak akan ditampilkan.</p>
                            </div>
                        </div>
                        <button type="button" @click="isAnonim = !isAnonim" 
                            :class="isAnonim ? 'bg-blue-900' : 'bg-gray-300'"
                            class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none">
                            <span :class="isAnonim ? 'translate-x-5' : 'translate-x-0'" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out"></span>
                        </button>
                    </div>
                    <div x-show="!isAnonim" x-transition>
                        <input type="text" name="nama" placeholder="Nama Anda (Opsional)" 
                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 text-sm">
                    </div>
                </div>

                <button type="submit" class="w-full py-3.5 bg-blue-950 text-white font-semibold rounded-xl hover:bg-blue-900 transition shadow-xs cursor-pointer">
                    Kirim
                </button>
            </form>
        </div>
    </main>

    <footer class="bg-white border-t border-gray-100 py-6 text-xs text-gray-500 mt-12">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center space-x-6">
                <div class="flex items-center space-x-2 text-blue-900 font-bold text-base">
                    <span class="p-1 bg-blue-900 text-white rounded text-xs">L</span>
                    <span>Lurah<span class="text-blue-500">Online</span></span>
                </div>
                <div class="border-l border-gray-200 pl-6 hidden md:block">
                    Platform Pengaduan Warga Desa Banjarsari
                </div>
            </div>
            <div class="flex items-center space-x-2 text-center md:text-right">
                <span>📍 Kantor Desa Banjarsari, Kec. Bayongbong, Kab. Garut<br><span class="text-gray-400">Senin–Jumat • 08.00–16.00</span></span>
            </div>
        </div>
    </footer>

</body>
</html>