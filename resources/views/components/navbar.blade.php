@php
$menus = [
    [
        'title' => 'Beranda',
        'url' => '/',
        'active' => Request::is('/') || Request::path() === '/' || Request::path() === '',
        'icon' => '<svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>'
    ],
    [
        'title' => 'Buat Pengaduan',
        'url' => '/pengaduan',
        'active' => Request::is('pengaduan'),
        'icon' => '<svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>'
    ],
    [
        'title' => 'Lacak Tiket',
        'url' => '/lacak',
        'active' => Request::is('lacak*'),
        'icon' => '<svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>'
    ],
    [
        'title' => 'Laporan Publik',
        'url' => '/laporan',
        'active' => Request::is('laporan'),
        'icon' => '<svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>'
    ],
];
@endphp
<nav class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-100 shadow-[0_4px_70px_-10px_rgba(0,71,171,0.20)]">
    <div class="mx-auto w-full max-w-[1283px] px-6 sm:px-8 lg:px-4 h-[81px] flex items-center justify-between">

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
            <ul class="flex items-center gap-1 lg:gap-2">
                @foreach ($menus as $menu)
                    <li>
                        <a href="{{ url($menu['url']) }}"
                           class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-sm lg:text-base font-bold transition-all duration-200 {{ $menu['active'] ? 'bg-[#0047AB]/10 text-[#0047AB] shadow-xs' : 'text-[#464646] hover:bg-slate-100/80 hover:text-[#0047AB]' }}">
                            {!! $menu['icon'] !!}
                            <span>{{ $menu['title'] }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="hidden md:block shrink-0 flex items-center gap-2">
            <button id="pwa-install-btn" class="hidden rounded-lg bg-[#0047AB] px-6 py-2 text-sm font-semibold text-white hover:bg-[#003780] transition-all shadow-sm">
                Pasang Aplikasi
            </button>
            <a href="{{ url('/admin')}}" class="rounded-lg bg-[#098A00] px-6 py-2 text-sm font-semibold text-white hover:bg-[#076c00] transition-all shadow-sm">
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
    <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-100 px-6 py-4 space-y-1">
        @foreach ($menus as $menu)
            <a href="{{ url($menu['url']) }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-base font-bold transition-colors {{ $menu['active'] ? 'bg-[#0047AB]/10 text-[#0047AB]' : 'text-[#464646] hover:bg-slate-50' }}">
                {!! $menu['icon'] !!}
                <span>{{ $menu['title'] }}</span>
            </a>
        @endforeach

        <button id="pwa-install-btn-mobile" class="hidden w-full mt-3 px-5 py-2 text-center rounded-[10px] bg-[#0047AB] text-white text-[18px] font-semibold">
            Pasang Aplikasi
        </button>
        <a href="{{ url('/admin') }}"
            class="block mt-3 px-5 py-2 text-center rounded-[10px] bg-[#098A00] text-white text-[18px] font-semibold hover:bg-[#076c00] transition-all shadow-sm">
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