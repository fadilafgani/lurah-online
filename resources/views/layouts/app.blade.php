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
        @yield('content')
    </main>

    <footer class="bg-white border-t border-slate-200">
                <div class="container mx-auto">

                    <div class="flex flex-col items-center justify-between gap-6 px-6 py-6 text-sm text-slate-500 md:flex-row">

                        <div class="flex items-center gap-6">

                            <div class="flex items-center gap-3">
                                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-[#0047AB] to-[#001D45] shadow-sm">
                                    <img
                                        src="{{ asset('images/logo.svg') }}"
                                        alt="Logo"
                                        class="h-6 w-6"
                                    >
                                </div>

                                <span class="text-xl font-extrabold tracking-tight bg-gradient-to-r from-[#0047AB] to-[#001D45] bg-clip-text text-transparent">
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
                                class="w-6 h-6"
                            >
                                <path d="M12 21s-7-4.35-7-11a7 7 0 1 1 14 0c0 6.65-7 11-7 11z"/>
                                <circle cx="12" cy="10" r="2.5"/>
                            </svg>

                            <div class="leading-tight">
                                <p>
                                    Kantor Desa Banjarsari, Kec. Bayongbong, Kab. Garut
                                </p>

                                <p>
                                    Senin–Jumat • 08.00–16.00
                                </p>
                            </div>

                        </div>

                    </div>

                </div>
            </footer>

</body>
</html>