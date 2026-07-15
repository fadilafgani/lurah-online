@php
$menus = [
    ['title' => 'Beranda', 'url' => '/', 'active' => Request::is('/')],
    ['title' => 'Buat Pengaduan', 'url' => '/pengaduan', 'active' => Request::is('pengaduan')],
    ['title' => 'Lacak Tiket', 'url' => '/lacak', 'active' => Request::is('lacak')],
    ['title' => 'Laporan Publik', 'url' => '/laporan', 'active' => Request::is('laporan')],
];
@endphp
<nav class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-100 shadow-[0_4px_70px_-10px_rgba(0,71,171,0.20)]">
    <div class="w-full px-4 sm:px-6 lg:px-8 h-[81px] flex items-center justify-between">

        <!-- Logo -->
        <a href="{{ url('/')}}" class="flex items-center gap-2 sm:gap-3 group shrink-0">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-transparent shadow-sm">
                    <img src="{{ asset('images/LurahOnline-logo.png') }}" alt="Logo" class="w-6 h-6 sm:w-8 sm:h-8">
                </div>
            <span class="text-lg sm:text-xl font-bold tracking-tight bg-gradient-to-r from-[#0047AB] to-[#001D45] bg-clip-text text-transparent">
                LurahOnline
            </span>
        </a>

        <!-- Desktop Menu -->
        <div class="hidden md:block">
            <ul class="flex items-center gap-1 lg:gap-2 text-[#464646]">
                <li>
                    <a href="{{ url('/')}}" class="whitespace-nowrap px-3 lg:px-4 py-2 text-sm font-medium hover:text-[#0047AB] transition-colors">
                        Beranda
                    </a>
                </li>
                <li>
                    <a href="{{ url('/pengaduan')}}" class="whitespace-nowrap px-3 lg:px-4 py-2 text-sm font-medium hover:text-[#0047AB] transition-colors">
                        Buat Pengaduan
                    </a>
                </li>
                <li>
                    <a href="{{ url('/lacak')}}" class="whitespace-nowrap px-3 lg:px-4 py-2 text-sm font-medium hover:text-[#0047AB] transition-colors">
                        Lacak Tiket
                    </a>
                </li>
                <li>
                    <a href="{{ url('/laporan')}}" class="whitespace-nowrap px-3 lg:px-4 py-2 text-sm font-medium hover:text-[#0047AB] transition-colors">
                        Laporan Publik
                    </a>
                </li>
            </ul>
        </div>

        <div class="hidden md:block shrink-0 flex items-center gap-2">
            <button id="pwa-install-btn" class="hidden rounded-lg bg-[#0047AB] px-6 py-2 text-sm font-semibold text-white hover:bg-[#003780] transition-all shadow-sm">
                Pasang Aplikasi
            </button>
            <a href="{{ url('/admin')}}" class="rounded-lg border border-slate-300 px-6 py-2 text-sm font-semibold text-[#464646] hover:bg-slate-100 transition-all shadow-sm">
                Admin
            </a>
        </div>

        <!-- Mobile Hamburger Button -->
        <button id="menu-button" type="button" aria-label="Buka menu"
            class="md:hidden inline-flex items-center justify-center p-2 -mr-2 rounded-lg text-[#464646] hover:bg-slate-100 transition-colors shrink-0">
            <svg id="menu-icon-open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-7 h-7">
                <line x1="3" y1="6" x2="21" y2="6"/>
                <line x1="3" y1="12" x2="21" y2="12"/>
                <line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
            <svg id="menu-icon-close" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="hidden w-7 h-7">
                <line x1="18" y1="6" x2="6" y2="18"/>
                <line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-100 px-6 pb-4">

        <a href="{{ url('/') }}"
            class="block py-3 text-[18px] font-semibold border-b border-gray-50 {{ Request::is('/') ? 'text-[#0047AB]' : 'text-[#464646]' }}">
            Beranda
        </a>

        <a href="{{ url('/pengaduan') }}"
            class="block py-3 text-[18px] font-semibold border-b border-gray-50 {{ Request::is('pengaduan') ? 'text-[#0047AB]' : 'text-[#464646]' }}">
            Buat Pengaduan
        </a>

        <a href="{{ url('/lacak') }}"
            class="block py-3 text-[18px] font-semibold border-b border-gray-50 {{ Request::is('lacak') ? 'text-[#0047AB]' : 'text-[#464646]' }}">
            Lacak Tiket
        </a>

        <a href="{{ url('/laporan') }}"
            class="block py-3 text-[18px] font-semibold border-b border-gray-50 {{ Request::is('laporan') ? 'text-[#0047AB]' : 'text-[#464646]' }}">
            Laporan Publik
        </a>

        <button id="pwa-install-btn-mobile" class="hidden w-full mt-3 px-5 py-2 text-center rounded-[10px] bg-[#0047AB] text-white text-[18px] font-semibold">
            Pasang Aplikasi
        </button>
        <a href="{{ url('/admin') }}"
            class="block mt-3 px-5 py-2 text-center rounded-[10px] border border-[#A19E9E] text-[#464646] text-[18px] font-semibold">
            Admin
        </a>

    </div>
</nav>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const button = document.getElementById("menu-button");
    const menu = document.getElementById("mobile-menu");
    const iconOpen = document.getElementById("menu-icon-open");
    const iconClose = document.getElementById("menu-icon-close");

    button?.addEventListener("click", function () {
        menu.classList.toggle("hidden");
        iconOpen.classList.toggle("hidden");
        iconClose.classList.toggle("hidden");
    });

    // PWA Install Prompt Logic
    let deferredPrompt;
    const installBtn = document.getElementById('pwa-install-btn');
    const installBtnMobile = document.getElementById('pwa-install-btn-mobile');

    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        installBtn?.classList.remove('hidden');
        installBtnMobile?.classList.remove('hidden');
    });

    const handleInstall = async () => {
        if (!deferredPrompt) return;
        deferredPrompt.prompt();
        const { outcome } = await deferredPrompt.userChoice;
        if (outcome === 'accepted') {
            deferredPrompt = null;
            installBtn?.classList.add('hidden');
            installBtnMobile?.classList.add('hidden');
        }
    };

    installBtn?.addEventListener('click', handleInstall);
    installBtnMobile?.addEventListener('click', handleInstall);
});
</script>