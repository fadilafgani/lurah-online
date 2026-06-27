@php
$menus = [
    ['title' => 'Beranda', 'url' => '/', 'active' => Request::is('/')],
    ['title' => 'Buat Pengaduan', 'url' => '/pengaduan', 'active' => Request::is('pengaduan')],
    ['title' => 'Lacak Tiket', 'url' => '/lacak', 'active' => Request::is('lacak')],
    ['title' => 'Laporan Publik', 'url' => '/laporan', 'active' => Request::is('laporan')],
];
@endphp
<nav class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-100 shadow-[0_4px_70px_-10px_rgba(0,71,171,0.20)]">
    <div class="w-full px-6 lg:px-8 h-[81px] flex items-center justify-between">

        <!-- Logo -->
        <a href="{{ url('/')}}" class="flex items-center gap-3 group">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-[#0047AB] to-[#001D45]">
                    <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="w-8 h-8">
                </div>
            <span class="text-xl font-bold tracking-tight bg-gradient-to-r from-[#0047AB] to-[#001D45] bg-clip-text text-transparent">
                LurahOnline
            </span>
        </a>

        <!-- Desktop Menu -->
        <nav class="hidden md:block">
            <ul class="flex items-center gap-2 text-[#464646]">
                <li>
                    <a href="{{ url('/')}}" class="px-4 py-2 text-sm font-medium hover:text-[#0047AB] transition-colors">
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

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden lg:hidden bg-white border-t border-gray-100 px-6 pb-4">

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

    button.addEventListener("click", function () {
        menu.classList.toggle("hidden");
    });
});
</script>