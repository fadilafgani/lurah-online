<nav class="fixed top-0 left-0 right-0 z-50 bg-white shadow-[0_4px_70px_-10px_rgba(0,71,171,0.20)]">
    <div class="w-full px-4 sm:px-6 lg:px-[59px] h-[81px] flex items-center justify-between gap-2 sm:gap-4 lg:gap-6">

        <!-- Logo -->
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 sm:gap-3 shrink-0 min-w-0">
            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-transparent shadow-sm">
                <img src="{{ asset('images/LurahOnline-logo.png') }}" alt="Logo" class="w-6 h-6 sm:w-8 sm:h-8">
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
            <div class="relative">
                <button type="button" id="notification-btn" aria-label="Notifikasi" class="relative text-[#464646] shrink-0 p-1 rounded-lg hover:bg-slate-100 transition-colors">
                    <svg width="24" height="24" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg" class="sm:w-7 sm:h-7">
                        <path d="M7.5 23.75V12.5C7.5 10.5109 8.29018 8.60322 9.6967 7.1967C11.1032 5.79018 13.0109 5 15 5C16.9891 5 18.8968 5.79018 20.3033 7.1967C21.7098 8.60322 22.5 10.5109 22.5 12.5V23.75M7.5 23.75H22.5M7.5 23.75H5M22.5 23.75H25M13.75 27.5H16.25" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M15 5C15.6904 5 16.25 4.44036 16.25 3.75C16.25 3.05964 15.6904 2.5 15 2.5C14.3096 2.5 13.75 3.05964 13.75 3.75C13.75 4.44036 14.3096 5 15 5Z" stroke="currentColor" stroke-width="2"/>
                    </svg>
                    <span id="notification-badge" class="absolute -top-1 -right-1 hidden h-5 w-5 items-center justify-center rounded-full bg-red-600 text-[10px] font-bold text-white leading-none">0</span>
                </button>
                <div id="notification-dropdown" class="absolute right-0 mt-2 w-80 hidden rounded-xl border border-slate-200 bg-white py-2 shadow-lg z-50">
                    <div class="px-4 py-2 font-bold text-slate-700 border-b border-slate-100 text-sm">
                        Pengaduan Baru
                    </div>
                    <div id="notification-list" class="max-h-60 overflow-y-auto divide-y divide-slate-100">
                        <p class="px-4 py-3 text-center text-slate-400 text-xs">Memuat...</p>
                    </div>
                </div>
            </div>

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
    // Polling notifications
    const notifBtn = document.getElementById("notification-btn");
    const notifDropdown = document.getElementById("notification-dropdown");
    const notifBadge = document.getElementById("notification-badge");
    const notifList = document.getElementById("notification-list");

    let readComplaints = JSON.parse(localStorage.getItem('read_complaints') || '[]');
    let knownTickets = [];
    let badgeCleared = false;

    notifBtn?.addEventListener("click", function (e) {
        e.stopPropagation();
        notifDropdown.classList.toggle("hidden");
        if (!notifDropdown.classList.contains("hidden")) {
            badgeCleared = true;
            notifBadge.classList.add("hidden");
        }
    });

    document.addEventListener("click", function () {
        notifDropdown?.classList.add("hidden");
    });

    function fetchNotifications() {
        fetch("{{ route('admin.api.notifications') }}")
            .then(res => res.json())
            .then(data => {
                let activeItems = data.items.filter(item => !readComplaints.includes(item.ticket_code));

                // If new tickets arrived, reset badge cleared status
                let hasNew = activeItems.some(item => !knownTickets.includes(item.ticket_code));
                if (hasNew) {
                    badgeCleared = false;
                    knownTickets = activeItems.map(item => item.ticket_code);
                }

                if (activeItems.length > 0 && !badgeCleared) {
                    notifBadge.textContent = activeItems.length;
                    notifBadge.classList.remove("hidden");
                    notifBadge.classList.add("flex");
                } else {
                    notifBadge.classList.add("hidden");
                }

                if (activeItems.length === 0) {
                    notifList.innerHTML = `<p class="px-4 py-3 text-center text-slate-400 text-xs">Tidak ada pengaduan baru</p>`;
                } else {
                    notifList.innerHTML = activeItems.map(item => `
                        <a href="/admin/dashboard?status=verifikasi&kode=${item.ticket_code}" 
                           onclick="markAsRead('${item.ticket_code}')"
                           class="block px-4 py-3 hover:bg-slate-50 transition-colors">
                            <p class="text-xs font-semibold text-slate-700">${item.title}</p>
                            <p class="text-[10px] text-slate-400 mt-1 font-mono">${item.ticket_code} • ${item.time}</p>
                        </a>
                    `).join('');
                }
            })
            .catch(err => console.error("Gagal mengambil notifikasi", err));
    }

    window.markAsRead = function(kode) {
        readComplaints.push(kode);
        localStorage.setItem('read_complaints', JSON.stringify(readComplaints));
    };

    fetchNotifications();
    setInterval(fetchNotifications, 10000);
});
</script>