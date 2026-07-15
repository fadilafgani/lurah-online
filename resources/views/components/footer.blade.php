<footer class="bg-white border-t border-slate-200">
    <div class="container mx-auto">
        <div class="flex flex-col items-center justify-between gap-6 px-6 py-6 text-sm text-slate-500 md:flex-row">

            <!-- Left -->
            <div class="flex items-center gap-6">

                <a href="{{ url('/') }}" class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-transparent shadow-sm">
                        <img src="{{ asset('images/LurahOnline-logo.png') }}" alt="Logo" class="w-6 h-6 sm:w-8 sm:h-8">
                    </div>

                    <span class="text-xl font-bold tracking-tight bg-gradient-to-r from-[#0047AB] to-[#001D45] bg-clip-text text-transparent">
                        LurahOnline
                    </span>
                </a>

                <div class="hidden h-10 w-px bg-slate-200 md:block"></div>

                <p class="hidden md:block">
                    Platform Pengaduan Warga Desa Banjarsari
                </p>

            </div>

            <!-- Right -->
            <div class="flex items-start gap-3">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="#6B7280"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="h-6 w-6 flex-shrink-0">
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