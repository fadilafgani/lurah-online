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
                            <a href="{{ url('/')}}" class="rounded-lg bg-slate-100 px-4 py-2 text-sm font-bold ">
                                Beranda
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/pengaduan')}}" class="px-4 py-2 text-sm font-medium hover:text-[#0047AB] transition-colors">
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

        <main class="w-full">
            <section class="relative min-h-[720px] overflow-hidden">

                <img
                    src="{{ asset('images/background_home.jpg') }}"
                    alt="Background"
                    class="absolute inset-0 h-full w-full object-cover">

                <div class="absolute inset-0 bg-[#153655]/60"></div>

                <div class="absolute inset-0 bg-gradient-to-b from-transparent from-50% via-[#153655] via-90% to-[#F8FAFC] to-100%"></div>
                <div class="relative z-10 flex min-h-[720px] items-center justify-center">
                    <div class="mx-auto max-w-3xl px-6 text-center">

                        <h1 class="text-5xl font-extrabold leading-tight tracking-tight bg-gradient-to-r from-[#FFFFFF] to-[#FFF2C7] bg-clip-text text-transparent md:text-7xl">
                            Suara Warga,
                            <br>
                            Aksi Nyata
                        </h1>

                        <p class="mx-auto mt-6 max-w-2xl font-semibold leading-relaxed text-white">
                            Sampaikan keluhan, lacak progresnya, dan beri penilaian.
                            <br>
                            Semua dalam satu platform yang sederhana dan transparan.
                        </p>

                        <div class="mt-10 flex flex-wrap items-center justify-center gap-4">
                            <a href="{{ url('/pengaduan')}}"
                                class="inline-flex items-center gap-2 rounded-xl bg-white px-7 py-3 text-sm font-semibold text-[#153655] transition">
                                Buat Pengaduan 
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="w-5 h-5">
                                    <path d="M5 12h14"/>
                                    <path d="M13 5l7 7-7 7"/>
                                </svg>
                            </a>

                            <a href="{{ url('/lacak')}}"
                                class="inline-flex items-center gap-2 rounded-xl border border-white/30 bg-white/10 px-7 py-3 text-sm font-semibold text-white backdrop-blur-md transition hover:bg-white/20">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    class="w-5 h-5">
                                <circle
                                    cx="10"
                                    cy="10"
                                    r="6"
                                    fill="#D9F2FF"
                                    stroke="#A7D8F5"
                                    stroke-width="1.5"/>

                                <circle
                                    cx="8"
                                    cy="8"
                                    r="1.5"
                                    fill="white"
                                    opacity="0.8"/>

                                <path
                                    d="M14.5 14.5L20 20"
                                    stroke="#8B5E3C"
                                    stroke-width="2.5"
                                    stroke-linecap="round"/>
                                </svg> Lacak Tiket
                            </a>
                        </div>

                        <div class="mt-8 flex flex-wrap items-center justify-center gap-6 text-sm text-white/80">
                            <div class="flex items-center gap-2">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    class="w-6 h-6">
                                <path
                                    d="M12 3L19 6V11C19 16 15.5 19.5 12 21C8.5 19.5 5 16 5 11V6L12 3Z"
                                    stroke="#22C55E"
                                    stroke-width="2"
                                    stroke-linejoin="round"/>
                                <path
                                    d="M9 11.5L11.2 13.7L15 10"
                                    stroke="#22C55E"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"/>
                                </svg>Anonim Tersedia
                            </div>

                            <div class="flex items-center gap-2">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    class="w-6 h-6">
                                    <path
                                        d="M13 2L5 13H11L9 22L19 10H13V2Z"
                                        stroke="#F4C430"
                                        stroke-width="2"
                                        stroke-linejoin="round"
                                        stroke-linecap="round"/>

                                    <path
                                        d="M12.5 4L7 12H11.5L10 18.5L17 10H12.5V4Z"
                                        fill="#FFD95A"
                                        opacity="0.15"/>
                                </svg>Verifikasi Cepat
                            </div>
                        </div>   
                    </div>
                </div>
            </section>

            <section class="container mx-auto px-6 py-24">
                <div class="text-center">
                    <h2 class="text-4xl font-extrabold text-[#153655]">
                        Bagaimana cara kerjanya?
                    </h2>

                    <p class="mt-3 text-slate-500">
                        Empat langkah sederhana, dari laporan hingga selesai.
                    </p>
                </div>

                <div class="mt-16 grid gap-6 md:grid-cols-2 lg:grid-cols-4">

                    <div class="relative rounded-3xl border border-slate-200 bg-white p-8 shadow-lg transition hover:-translate-y-1 hover:shadow-xl">
                        <div class="absolute -right-3 -top-3 flex h-14 w-14 items-center justify-center rounded-full bg-[#123B66] text-2xl font-bold text-white shadow-md">
                            1
                        </div>

                        <div class="mb-6 flex h-20 w-20 items-center justify-center rounded-2xl bg-[#DCEBED] text-4xl">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="64" height="64" fill="none">
                            <!-- Clipboard -->
                            <rect x="14" y="12" width="36" height="40" rx="4"
                                    stroke="#183A5A" stroke-width="4"/>
                            <!-- Top clip -->
                            <path d="M24 12h16v-2a4 4 0 0 0-4-4h-8a4 4 0 0 0-4 4v2z"
                                    fill="#183A5A"/>
                            <!-- Text lines -->
                            <line x1="22" y1="24" x2="38" y2="24"
                                    stroke="#183A5A" stroke-width="4" stroke-linecap="round"/>
                            <line x1="22" y1="32" x2="34" y2="32"
                                    stroke="#183A5A" stroke-width="4" stroke-linecap="round"/>
                            <line x1="22" y1="40" x2="30" y2="40"
                                    stroke="#183A5A" stroke-width="4" stroke-linecap="round"/>
                            <!-- Pencil -->
                            <g transform="rotate(-45 46 42)">
                                <rect x="38" y="36" width="18" height="6" rx="2" fill="#183A5A"/>
                                <polygon points="56,36 62,39 56,42" fill="#183A5A"/>
                            </g>
                            </svg>
                        </div>

                        <h3 class="text-2xl font-bold text-black text-center">
                            Lapor
                        </h3>

                        <p class="mt-4 text-base leading-relaxed text-slate-600 text-center">
                            Isi formulir pengaduan
                            dengan foto bukti.
                        </p>
                    </div>


                    <div class="relative rounded-3xl border border-slate-200 bg-white p-8 shadow-lg transition hover:-translate-y-1 hover:shadow-xl">
                        <div class="absolute -right-3 -top-3 flex h-14 w-14 items-center justify-center rounded-full bg-[#123B66] text-2xl font-bold text-white shadow-md">
                            2
                        </div>
                        <div class="mb-6 flex h-20 w-20 items-center justify-center rounded-2xl bg-[#DCEBED] text-4xl">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 64 64"
                                width="64"
                                height="64"
                                fill="none">
                            <!-- Shield -->
                            <path
                                d="M32 8
                                L50 14
                                V30
                                C50 42 42 52 32 56
                                C22 52 14 42 14 30
                                V14
                                Z"
                                stroke="#183A5A"
                                stroke-width="4"
                                stroke-linejoin="round"/>
                            <!-- Check -->
                            <path
                                d="M24 32 L30 38 L41 26"
                                stroke="#183A5A"
                                stroke-width="4"
                                stroke-linecap="round"
                                stroke-linejoin="round"/>
                            </svg>
                        </div>

                        <h3 class="text-2xl font-bold text-black">
                            Verifikasi
                        </h3>

                        <p class="mt-4 text-base leading-relaxed text-slate-600">
                            Admin kelurahan
                            memverifikasi laporan.
                        </p>
                    </div>

                    <div class="relative rounded-3xl border border-slate-200 bg-white p-8 shadow-lg transition hover:-translate-y-1 hover:shadow-xl">
                        <div class="absolute -right-3 -top-3 flex h-14 w-14 items-center justify-center rounded-full bg-[#123B66] text-2xl font-bold text-white shadow-md">
                            3
                        </div>

                        <div class="mb-6 flex h-20 w-20 items-center justify-center rounded-2xl bg-[#DCEBED] text-4xl">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 64 64"
                                width="128"
                                height="128"
                                fill="none">
                            <!-- Background -->
                            <rect x="2" y="2" width="60" height="60" rx="12"
                                    fill="#D7E4E8"/>
                            <!-- Left Person -->
                            <circle cx="28" cy="26" r="5" fill="#183A5A"/>
                            <path d="M20 43
                                    V38
                                    C20 34 25 32 28 32
                                    C31 32 36 34 36 38
                                    V43 Z"
                                    fill="#183A5A"/>
                            <!-- Right Person -->
                            <circle cx="41" cy="26" r="5" fill="#183A5A"/>
                            <path d="M34 43
                                    V38
                                    C34 34 38 32 41 32
                                    C44 32 48 34 48 38
                                    V43 Z"
                                    fill="#183A5A"/>
                            </svg>
                        </div>

                        <h3 class="text-2xl font-bold text-black">
                            Tindak Lanjut
                        </h3>

                        <p class="mt-4 text-base leading-relaxed text-slate-600">
                            Unit terkait menangani
                            di lapangan.
                        </p>
                    </div>

                    <div class="relative rounded-3xl border border-slate-200 bg-white p-8 shadow-lg transition hover:-translate-y-1 hover:shadow-xl">
                        <div class="absolute -right-3 -top-3 flex h-14 w-14 items-center justify-center rounded-full bg-[#123B66] text-2xl font-bold text-white shadow-md">
                            4
                        </div>

                        <div class="mb-6 flex h-20 w-20 items-center justify-center rounded-2xl bg-[#DCEBED] text-4xl">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 64 64"
                                width="128"
                                height="128"
                                fill="none">
                            <!-- Background -->
                            <rect width="64" height="64" rx="12" fill="#D7E4E8"/>
                            <!-- Star -->
                            <path
                                d="M32 18
                                L36 27
                                L46 28
                                L39 35
                                L41 45
                                L32 40
                                L23 45
                                L25 35
                                L18 28
                                L28 27
                                Z"
                                stroke="#183A5A"
                                stroke-width="4"
                                stroke-linejoin="round"/>
                            </svg>
                        </div>

                        <h3 class="text-2xl font-bold text-black">
                            Selesai & Nilai
                        </h3>

                        <p class="mt-4 text-base leading-relaxed text-slate-600">
                            Beri rating untuk hasil
                            yang Anda terima.
                        </p>
                    </div>
                </div>
            </section>

            <section class="container mx-auto px-6 pb-24">
                <div class="overflow-hidden rounded-[32px] bg-gradient-to-r from-[#153655] to-[#2E77BB] px-8 py-20 text-center shadow-2xl">

                    <h2 class="text-4xl font-extrabold text-[#FFFBED]">
                        Siap menyampaikan keluhan Anda?
                    </h2>

                    <p class="mt-4 text-lg text-white/80">
                        Setiap laporan adalah langkah menuju lingkungan yang lebih baik.
                    </p>

                    <div class="mt-10">
                        <a href="{{ url('/pengaduan')}}" class="inline-flex items-center gap-2 rounded-xl bg-white px-8 py-4 text-sm font-bold text-[#001D45] transition hover:scale-105">
                            Mulai Sekarang
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="w-5 h-5">
                                <path d="M5 12h14"/>
                                <path d="M13 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </section>

            <footer class="bg-white border-t border-slate-200">
                <div class="container mx-auto">

                    <div class="flex flex-col items-center justify-between gap-6 px-6 py-6 text-sm text-slate-500 md:flex-row">
                        <div class="flex items-center gap-6">
                            <div class="flex items-center gap-3">
                                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-[#0047AB] to-[#001D45] shadow-sm">
                                    <img
                                        src="{{ asset('images/logo.svg') }}"
                                        alt="Logo"
                                        class="h-6 w-6">
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
            
        </main>
    </body>
</html>