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
        <main class="min-h-[calc(100vh-80px-88px)] flex items-center justify-center px-4 py-16"
              style="background: linear-gradient(135deg, #e8eef7 0%, #dce6f5 50%, #e4eaf6 100%);">
 
            <div class="w-full max-w-2xl text-center">
 
                <h1 class="text-4xl font-extrabold tracking-tight bg-gradient-to-r from-[#0047AB] to-[#153655] bg-clip-text text-transparent mb-3">
                    Lacak Pengaduan
                </h1>
                <p class="text-[#464646] mb-8">
                    Masukkan kode tiket Anda untuk melihat status terkini.
                </p>
 
                {{-- FORM LACAK --}}
                <form action="{{ route('lacak.cari') }}" method="GET">
                    <div class="flex items-center bg-white rounded-full shadow-lg px-5 py-2 gap-3">
                        {{-- Icon kaca pembesar kuning --}}
                        <svg class="text-yellow-400 shrink-0" width="20" height="20" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2.5">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.35-4.35"/>
                        </svg>
 
                        <input
                            type="text"
                            name="kode"
                            value="{{ old('kode', request('kode')) }}"
                            placeholder="Contoh: LO-12345-AB789"
                            autocomplete="off"
                            class="flex-1 border-none outline-none bg-transparent text-sm text-slate-700 placeholder-gray-400 py-2"
                        />
 
                        <button type="submit"
                            class="bg-gradient-to-br from-[#0047AB] to-[#001D45] text-white text-sm font-semibold px-6 py-3 rounded-full hover:opacity-90 transition-all shrink-0">
                            Lacak
                        </button>
                    </div>
                </form>
 
                {{-- HASIL TIKET DITEMUKAN --}}
                @isset($tiket)
                <div class="mt-8 bg-white rounded-2xl shadow-lg border border-gray-100 p-8 text-left">
 
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Kode Tiket</p>
                            <p class="text-sm font-bold text-[#0047AB]">{{ $tiket->kode_tiket }}</p>
                        </div>
 
                        @php
                            $badgeConfig = match($tiket->status) {
                                'Diproses'  => ['bg-amber-100 text-amber-700',  'Diproses'],
                                'Selesai'   => ['bg-emerald-100 text-emerald-700', 'Selesai'],
                                default     => ['bg-gray-100 text-gray-500', $tiket->status],
                            };
                        @endphp
 
                        <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $badgeConfig[0] }}">
                            {{ $badgeConfig[1] }}
                        </span>
                    </div>
 
                    <h2 class="text-lg font-extrabold text-slate-800 mb-1">{{ $tiket->judul }}</h2>
                    <p class="text-xs text-gray-400 mb-5">
                        Dikirim pada {{ \Carbon\Carbon::parse($tiket->created_at)->translatedFormat('d F Y') }}
                    </p>
 
                    <hr class="border-gray-100 mb-5">
 
                    <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-1">Deskripsi</p>
                    <p class="text-sm text-slate-700 leading-relaxed">{{ $tiket->deskripsi }}</p>
 
                    @if($tiket->lokasi)
                        <hr class="border-gray-100 my-5">
                        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-1">Lokasi</p>
                        <p class="text-sm text-slate-700">{{ $tiket->lokasi }}</p>
                    @endif
 
                    @if($tiket->catatan_admin)
                        <hr class="border-gray-100 my-5">
                        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-1">Catatan Admin</p>
                        <p class="text-sm text-slate-700 leading-relaxed">{{ $tiket->catatan_admin }}</p>
                    @endif
 
                </div>
                @endisset
 
                {{-- TIKET TIDAK DITEMUKAN --}}
                @if(session('error'))
                <div class="mt-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-sm">
                    {{ session('error') }}
                </div>
                @endif
 
            </div>
        </main>
        <footer class="bg-white border-t border-slate-200">
        <div class="container mx-auto">
         <div class="flex flex-col items-center justify-between gap-6 px-6 py-6 text-sm text-slate-500 md:flex-row">
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-[#0047AB] to-[#001D45] shadow-sm">
                        <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="h-6 w-6">
                    </div>

                    <span class="text-xl font-bold tracking-tight bg-gradient-to-r from-[#0047AB] to-[#001D45] bg-clip-text text-transparent">
                        LurahOnline
                    </span>
                </div>

                <div class="hidden h-10 w-px bg-slate-200 md:block"></div>
                    <p class="hidden md:block">
                        Platform Pengaduan Warga Desa Banjarsari
                        </p>
                </div>

                <div class="flex items-start gap-3 text-right">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="#6B7280"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="w-6 h-6">
                        <path d="M12 21s-7-4.35-7-11a7 7 0 1 1 14 0c0 6.65-7 11-7 11z"/>
                        <circle cx="12" cy="10" r="2.5"/>
                    </svg>

                    <div class="leading-tight text-left">
                        <p>Kantor Desa Banjarsari, Kec. Bayongbong, Kab. Garut</p>
                        <p>Senin–Jumat • 08.00–16.00</p>
                    </div>
                </div>
            </div>
        </div>
        </footer>
    </body>
</html>