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
    <body class="min-h-screen flex flex-col bg-white text-slate-900 antialiased">
        @include('components.navbar')
        <main class="flex-1 pt-24 px-4 pb-16 flex items-start justify-center"
        style="background: linear-gradient(135deg,#e8eef7 0%,#dce6f5 50%,#e4eaf6 100%);">
 
            <div class="w-full max-w-2xl mx-auto text-center">
                <h1 class="text-5xl font-extrabold leading-[1.3] bg-gradient-to-r from-[#0047AB] to-[#153655] bg-clip-text text-transparent mb-3">
                    Lacak Pengaduan
                </h1>

                <p class="text-xl text-[#464646] mb-8">
                    Masukkan kode tiket Anda untuk melihat status terkini.
                </p>
 
                {{-- FORM LACAK --}}
                <form action="{{ route('lacak.cari') }}" method="GET" class="mt-8">
                    <div class="flex items-center bg-white rounded-full shadow-[0_8px_24px_rgba(0,0,0,0.12)] pl-8 pr-2 py-2 max-w-3xl mx-auto">

                        <svg class="text-[#F4B400] shrink-0" width="24" height="24"
                            viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="M21 21l-4.35-4.35"/>
                        </svg>

                        <input
                            type="text"
                            name="kode"
                            value="{{ old('kode', request('kode')) }}"
                            placeholder="Contoh: LO-12345-AB789"
                            autocomplete="off"
                            class="flex-1 bg-transparent border-0 outline-none focus:ring-0 px-5 text-lg placeholder:text-gray-400"
                        />

                        <button
                            type="submit"
                            class="bg-gradient-to-r from-[#0047AB] to-[#0D2E63] text-white font-semibold rounded-full px-10 py-4 text-lg hover:opacity-90 transition"
                        >
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
        @include('components.footer')
    </body>
</html>