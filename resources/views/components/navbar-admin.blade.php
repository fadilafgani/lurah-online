<nav class="fixed top-0 left-0 right-0 z-50 bg-white shadow-[0_4px_70px_-10px_rgba(0,71,171,0.20)]">
    <div class="w-full px-4 sm:px-6 lg:px-[59px] h-[81px] flex items-center justify-between gap-2 sm:gap-4 lg:gap-6">

        <!-- Logo -->
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 sm:gap-3 shrink-0 min-w-0">
            <div class="flex h-10 w-10 sm:h-12 sm:w-12 items-center justify-center rounded-xl bg-gradient-to-br from-[#0047AB] to-[#153655] shrink-0">
                <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="w-6 h-6 sm:w-8 sm:h-8">
            </div>
            <span class="text-base sm:text-xl font-bold tracking-tight bg-gradient-to-r from-[#0047AB] to-[#001D45] bg-clip-text text-transparent truncate">
                LurahOnline
            </span>
        </a>

        <!-- Menu (desktop) -->
        <ul class="hidden md:flex items-center gap-5">
            <li>
                <a href="{{ route('admin.dashboard') }}"
                    class="whitespace-nowrap text-[20px] font-semibold transition-colors {{ Request::routeIs('admin.dashboard') ? 'text-[#0047AB]' : 'text-[#464646] hover:text-[#0047AB]' }}">
                    Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('admin.laporan') }}"
                    class="whitespace-nowrap text-[20px] font-semibold transition-colors {{ Request::routeIs('admin.laporan') ? 'text-[#0047AB]' : 'text-[#464646] hover:text-[#0047AB]' }}">
                    Laporan
                </a>
            </li>
            <li>
                <a href="{{ route('admin.kata-terlarang') }}"
                    class="whitespace-nowrap text-[20px] font-semibold transition-colors {{ Request::routeIs('admin.kata-terlarang') ? 'text-[#0047AB]' : 'text-[#464646] hover:text-[#0047AB]' }}">
                    Kata Terlarang
                </a>
            </li>
            <li>
                <a href="{{ route('admin.akun-unit') }}"
                    class="whitespace-nowrap text-[20px] font-semibold transition-colors {{ Request::routeIs('admin.akun-unit') ? 'text-[#0047AB]' : 'text-[#464646] hover:text-[#0047AB]' }}">
                    Akun Unit
                </a>
            </li>
        </ul>

        <!-- Actions -->
        <div class="flex items-center gap-2 sm:gap-3 lg:gap-5 shrink-0">
            <button type="button" aria-label="Notifikasi" class="text-[#464646] shrink-0">
                <svg width="24" height="24" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg" class="sm:w-7 sm:h-7">
                    <path d="M7.5 23.75V12.5C7.5 10.5109 8.29018 8.60322 9.6967 7.1967C11.1032 5.79018 13.0109 5 15 5C16.9891 5 18.8968 5.79018 20.3033 7.1967C21.7098 8.60322 22.5 10.5109 22.5 12.5V23.75M7.5 23.75H22.5M7.5 23.75H5M22.5 23.75H25M13.75 27.5H16.25" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M15 5C15.6904 5 16.25 4.44036 16.25 3.75C16.25 3.05964 15.6904 2.5 15 2.5C14.3096 2.5 13.75 3.05964 13.75 3.75C13.75 4.44036 14.3096 5 15 5Z" stroke="currentColor" stroke-width="2"/>
                </svg>
            </button>

            <button type="button" id="admin-logout-btn"
                class="whitespace-nowrap rounded-[10px] border border-[#A19E9E] px-3 py-1.5 text-sm sm:px-4 sm:text-base lg:px-[23px] lg:py-[8px] lg:text-[20px] font-semibold text-[#464646] hover:bg-slate-50 transition shrink-0">
                Keluar
            </button>

            <!-- Mobile Hamburger Button -->
            <button id="admin-menu-button" type="button" aria-label="Buka menu"
                class="md:hidden inline-flex items-center justify-center p-1.5 -mr-1 rounded-lg text-[#464646] hover:bg-slate-100 transition-colors shrink-0">
                <svg id="admin-menu-icon-open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6">
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <line x1="3" y1="12" x2="21" y2="12"/>
                    <line x1="3" y1="18" x2="21" y2="18"/>
                </svg>
                <svg id="admin-menu-icon-close" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="hidden w-6 h-6">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="admin-mobile-menu" class="hidden md:hidden bg-white border-t border-gray-100 px-4 pb-4">
        <a href="{{ route('admin.dashboard') }}"
            class="block py-3 text-[16px] font-semibold border-b border-gray-50 {{ Request::routeIs('admin.dashboard') ? 'text-[#0047AB]' : 'text-[#464646]' }}">
            Dashboard
        </a>
        <a href="{{ route('admin.laporan') }}"
            class="block py-3 text-[16px] font-semibold border-b border-gray-50 {{ Request::routeIs('admin.laporan') ? 'text-[#0047AB]' : 'text-[#464646]' }}">
            Laporan
        </a>
        <a href="{{ route('admin.kata-terlarang') }}"
            class="block py-3 text-[16px] font-semibold border-b border-gray-50 {{ Request::routeIs('admin.kata-terlarang') ? 'text-[#0047AB]' : 'text-[#464646]' }}">
            Kata Terlarang
        </a>
        <a href="{{ route('admin.akun-unit') }}"
            class="block py-3 text-[16px] font-semibold {{ Request::routeIs('admin.akun-unit') ? 'text-[#0047AB]' : 'text-[#464646]' }}">
            Akun Unit
        </a>
    </div>
</nav>

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById('admin-logout-btn')?.addEventListener('click', function () {
        localStorage.removeItem('admin_token');
        window.location.href = "{{ route('admin.login') }}";
    });

    const menuBtn = document.getElementById("admin-menu-button");
    const menu = document.getElementById("admin-mobile-menu");
    const iconOpen = document.getElementById("admin-menu-icon-open");
    const iconClose = document.getElementById("admin-menu-icon-close");

    menuBtn?.addEventListener("click", function () {
        menu.classList.toggle("hidden");
        iconOpen.classList.toggle("hidden");
        iconClose.classList.toggle("hidden");
    });
});
</script>