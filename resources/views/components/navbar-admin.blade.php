<nav class="fixed top-0 left-0 right-0 z-50 bg-white shadow-[0_4px_70px_-10px_rgba(0,71,171,0.20)]">
    <div class="w-full px-6 lg:px-[59px] h-[81px] flex items-center justify-between gap-6">

        <!-- Logo -->
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 shrink-0">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-[#0047AB] to-[#153655]">
                <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="w-8 h-8">
            </div>
            <span class="text-xl font-bold tracking-tight bg-gradient-to-r from-[#0047AB] to-[#001D45] bg-clip-text text-transparent">
                LurahOnline
            </span>
        </a>

        <!-- Menu -->
        <ul class="hidden md:flex items-center gap-5">
            <li>
                <a href="{{ route('admin.dashboard') }}"
                    class="text-[20px] font-semibold transition-colors {{ Request::routeIs('admin.dashboard') ? 'text-[#0047AB]' : 'text-[#464646] hover:text-[#0047AB]' }}">
                    Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('admin.laporan') }}"
                    class="text-[20px] font-semibold transition-colors {{ Request::routeIs('admin.laporan') ? 'text-[#0047AB]' : 'text-[#464646] hover:text-[#0047AB]' }}">
                    Laporan
                </a>
            </li>
            <li>
                <a href="{{ route('admin.kata-terlarang') }}"
                    class="text-[20px] font-semibold transition-colors {{ Request::routeIs('admin.kata-terlarang') ? 'text-[#0047AB]' : 'text-[#464646] hover:text-[#0047AB]' }}">
                    Kata Terlarang
                </a>
            </li>
            <li>
                <a href="{{ route('admin.akun-unit') }}"
                    class="text-[20px] font-semibold transition-colors {{ Request::routeIs('admin.akun-unit') ? 'text-[#0047AB]' : 'text-[#464646] hover:text-[#0047AB]' }}">
                    Akun Unit
                </a>
            </li>
        </ul>

        <!-- Actions -->
        <div class="flex items-center gap-5 shrink-0">
            <button type="button" aria-label="Notifikasi" class="text-[#464646]">
                <svg width="28" height="28" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7.5 23.75V12.5C7.5 10.5109 8.29018 8.60322 9.6967 7.1967C11.1032 5.79018 13.0109 5 15 5C16.9891 5 18.8968 5.79018 20.3033 7.1967C21.7098 8.60322 22.5 10.5109 22.5 12.5V23.75M7.5 23.75H22.5M7.5 23.75H5M22.5 23.75H25M13.75 27.5H16.25" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M15 5C15.6904 5 16.25 4.44036 16.25 3.75C16.25 3.05964 15.6904 2.5 15 2.5C14.3096 2.5 13.75 3.05964 13.75 3.75C13.75 4.44036 14.3096 5 15 5Z" stroke="currentColor" stroke-width="2"/>
                </svg>
            </button>

            <button type="button" id="admin-logout-btn"
                class="rounded-[10px] border border-[#A19E9E] px-[23px] py-[8px] text-[20px] font-semibold text-[#464646] hover:bg-slate-50 transition">
                Keluar
            </button>
        </div>
    </div>
</nav>

<script>
document.getElementById('admin-logout-btn')?.addEventListener('click', function () {
    localStorage.removeItem('admin_token');
    window.location.href = "{{ route('admin.login') }}";
});
</script>
