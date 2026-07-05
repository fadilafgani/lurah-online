<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kata Terlarang Admin – LurahOnline</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-white text-slate-900 antialiased">

@include('components.navbar-admin')

<main class="relative overflow-hidden pt-[126px] pb-16">

    {{-- ── Ambient background blobs ── --}}
    <div aria-hidden="true" class="pointer-events-none absolute inset-x-0 top-[271px] -z-10 flex justify-between blur-[350px]">
        <div class="-ml-24 h-[280px] w-[280px] shrink-0 rounded-full bg-[#0047AB]"></div>
        <div class="-mr-24 h-[220px] w-[460px] shrink-0 rounded-full bg-[#00B4D8] opacity-60"></div>
    </div>

    <div class="mx-auto flex w-full max-w-[979px] flex-col items-center gap-[34px] px-6 sm:px-8 lg:px-4">

        @php
            $kataTerlarang = $kataTerlarang ?? [
                'tai', 'anjing', 'babi', 'sialan', 'bajingan', 'cino', 'bodat',
                'monyet', 'cina', 'tahi', 'kafir', 'jancok', 'cok',
                'goblok', 'asu', 'sialan', 'bego',
            ];
        @endphp

        {{-- ── Page Header ── --}}
        <div class="flex w-full items-center justify-center gap-3.5">
            <div class="flex h-[53px] w-[53px] shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[#0047AB] to-[#153655]">
                <svg width="33" height="33" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M29.1148 13.2541C28.6288 12.7463 28.1261 12.2229 27.9366 11.7627C27.7612 11.3412 27.7509 10.6425 27.7406 9.96574C27.7213 8.70762 27.7007 7.28191 26.7094 6.29063C25.7181 5.29934 24.2924 5.27871 23.0343 5.25937C22.3575 5.24906 21.6588 5.23875 21.2373 5.06344C20.7784 4.87395 20.2537 4.37121 19.7459 3.88523C18.8564 3.03059 17.8458 2.0625 16.5 2.0625C15.1542 2.0625 14.1449 3.03059 13.2541 3.88523C12.7463 4.37121 12.2229 4.87395 11.7627 5.06344C11.3438 5.23875 10.6425 5.24906 9.96574 5.25937C8.70762 5.27871 7.28191 5.29934 6.29063 6.29063C5.29934 7.28191 5.28516 8.70762 5.25937 9.96574C5.24906 10.6425 5.23875 11.3412 5.06344 11.7627C4.87395 12.2216 4.37121 12.7463 3.88523 13.2541C3.03059 14.1436 2.0625 15.1542 2.0625 16.5C2.0625 17.8458 3.03059 18.8551 3.88523 19.7459C4.37121 20.2537 4.87395 20.7771 5.06344 21.2373C5.23875 21.6588 5.24906 22.3575 5.25937 23.0343C5.27871 24.2924 5.29934 25.7181 6.29063 26.7094C7.28191 27.7007 8.70762 27.7213 9.96574 27.7406C10.6425 27.7509 11.3412 27.7612 11.7627 27.9366C12.2216 28.1261 12.7463 28.6288 13.2541 29.1148C14.1436 29.9694 15.1542 30.9375 16.5 30.9375C17.8458 30.9375 18.8551 29.9694 19.7459 29.1148C20.2537 28.6288 20.7771 28.1261 21.2373 27.9366C21.6588 27.7612 22.3575 27.7509 23.0343 27.7406C24.2924 27.7213 25.7181 27.7007 26.7094 26.7094C27.7007 25.7181 27.7213 24.2924 27.7406 23.0343C27.7509 22.3575 27.7612 21.6588 27.9366 21.2373C28.1261 20.7784 28.6288 20.2537 29.1148 19.7459C29.9694 18.8564 30.9375 17.8458 30.9375 16.5C30.9375 15.1542 29.9694 14.1449 29.1148 13.2541ZM27.6259 18.3189C27.0084 18.9634 26.3691 19.6298 26.03 20.4484C25.7052 21.2347 25.691 22.1332 25.6781 23.0033C25.6652 23.9057 25.6511 24.8505 25.2502 25.2502C24.8493 25.6498 23.9108 25.6652 23.0033 25.6781C22.1332 25.691 21.2347 25.7052 20.4484 26.03C19.6298 26.3691 18.9634 27.0084 18.3189 27.6259C17.6743 28.2434 17.0156 28.875 16.5 28.875C15.9844 28.875 15.3205 28.2408 14.6811 27.6259C14.0418 27.011 13.3702 26.3691 12.5516 26.03C11.7653 25.7052 10.8668 25.691 9.99668 25.6781C9.09434 25.6652 8.14945 25.6511 7.74984 25.2502C7.35023 24.8493 7.33477 23.9108 7.32188 23.0033C7.30898 22.1332 7.2948 21.2347 6.96996 20.4484C6.63094 19.6298 5.99156 18.9634 5.3741 18.3189C4.75664 17.6743 4.125 17.0156 4.125 16.5C4.125 15.9844 4.75922 15.3205 5.3741 14.6811C5.98898 14.0418 6.63094 13.3702 6.96996 12.5516C7.2948 11.7653 7.30898 10.8668 7.32188 9.99668C7.33477 9.09434 7.34895 8.14945 7.74984 7.74984C8.15074 7.35023 9.08918 7.33477 9.99668 7.32188C10.8668 7.30898 11.7653 7.2948 12.5516 6.96996C13.3702 6.63094 14.0366 5.99156 14.6811 5.3741C15.3257 4.75664 15.9844 4.125 16.5 4.125C17.0156 4.125 17.6795 4.75922 18.3189 5.3741C18.9582 5.98898 19.6298 6.63094 20.4484 6.96996C21.2347 7.2948 22.1332 7.30898 23.0033 7.32188C23.9057 7.33477 24.8505 7.34895 25.2502 7.74984C25.6498 8.15074 25.6652 9.08918 25.6781 9.99668C25.691 10.8668 25.7052 11.7653 26.03 12.5516C26.3691 13.3702 27.0084 14.0366 27.6259 14.6811C28.2434 15.3257 28.875 15.9844 28.875 16.5C28.875 17.0156 28.2408 17.6795 27.6259 18.3189ZM15.4688 17.5312V10.3125C15.4688 10.039 15.5774 9.77669 15.7708 9.5833C15.9642 9.3899 16.2265 9.28125 16.5 9.28125C16.7735 9.28125 17.0358 9.3899 17.2292 9.5833C17.4226 9.77669 17.5312 10.039 17.5312 10.3125V17.5312C17.5312 17.8048 17.4226 18.0671 17.2292 18.2605C17.0358 18.4539 16.7735 18.5625 16.5 18.5625C16.2265 18.5625 15.9642 18.4539 15.7708 18.2605C15.5774 18.0671 15.4688 17.8048 15.4688 17.5312ZM18.0469 22.1719C18.0469 22.4778 17.9562 22.7769 17.7862 23.0313C17.6162 23.2857 17.3746 23.4839 17.092 23.601C16.8093 23.7181 16.4983 23.7487 16.1982 23.689C15.8982 23.6293 15.6225 23.482 15.4062 23.2657C15.1899 23.0493 15.0425 22.7737 14.9828 22.4737C14.9232 22.1736 14.9538 21.8626 15.0709 21.5799C15.188 21.2973 15.3862 21.0557 15.6406 20.8857C15.895 20.7157 16.1941 20.625 16.5 20.625C16.9103 20.625 17.3037 20.788 17.5938 21.0781C17.8839 21.3682 18.0469 21.7616 18.0469 22.1719Z" fill="white"/>
                </svg>
            </div>
            <div class="flex w-[553px] flex-col items-center gap-0.5">
                <h1 class="self-stretch bg-gradient-to-r from-[#0047AB] to-[#153655] bg-clip-text text-[30px] font-extrabold leading-none text-transparent">
                    Kata Terlarang
                </h1>
                <p class="self-stretch text-[18px] font-medium text-[#464646]">Pengaduan yang memuat kata di daftar ini akan ditolak otomatis.</p>
            </div>
        </div>

        {{-- ── Tambah Kata Baru ── --}}
        <div class="w-full rounded-[20px] border-[0.5px] border-[#A19E9E] bg-white p-[37px] shadow-[0_4px_10px_0_rgba(0,0,0,0.20)] sm:p-8">
            <div class="flex flex-col items-start gap-[15px]">
                <h2 class="self-stretch text-[20px] font-extrabold text-[#153655]">Tambah Kata Baru</h2>
                <div class="flex w-full flex-col items-center gap-4 sm:flex-row">
                    <input id="kata-baru-input" type="text" placeholder="Ketik satu atau beberapa kata, pisahkan dengan koma/spasi"
                        class="w-full flex-1 rounded-[10px] border border-[#E5E5E5] bg-[#F8FAFC] px-5 py-[11px] text-[15px] font-medium text-[#464646] placeholder:text-[#656565] focus:outline-none focus:ring-1 focus:ring-[#0047AB]">
                    <button type="button" id="tambah-kata-btn"
                        class="w-full shrink-0 rounded-[10px] bg-gradient-to-r from-[#0047AB] to-[#153655] px-8 py-[9px] text-[16px] font-semibold text-white shadow-[2px_2px_4px_0_rgba(0,0,0,0.25)] sm:w-[130px]">
                        Tambah
                    </button>
                </div>
                <p class="self-stretch text-[15px] font-medium text-[#464646]">Tip: kata dicocokkan tanpa membedakan huruf besar/kecil dan tahan bypass leetspeak (mis. 4nj1ng).</p>
            </div>
        </div>

        {{-- ── Daftar Kata ── --}}
        <div class="w-full rounded-[20px] border-[0.5px] border-[#A19E9E] bg-white shadow-[0_4px_10px_0_rgba(0,0,0,0.20)]">
            <div class="flex flex-wrap items-center justify-between gap-4 border-b-[0.5px] border-[#A19E9E] bg-[#F9F9F9] px-10 py-5">
                <h2 id="daftar-kata-title" class="text-[20px] font-extrabold text-[#153655]">Daftar Kata ({{ count($kataTerlarang) }})</h2>
                <div class="relative w-full sm:w-[240px]">
                    <svg class="pointer-events-none absolute left-5 top-1/2 -translate-y-1/2" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8.25 14.25C11.5637 14.25 14.25 11.5637 14.25 8.25C14.25 4.93629 11.5637 2.25 8.25 2.25C4.93629 2.25 2.25 4.93629 2.25 8.25C2.25 11.5637 4.93629 14.25 8.25 14.25Z" stroke="#656565" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M15.75 15.75L12.4875 12.4875" stroke="#656565" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <input id="cari-kata-input" type="text" placeholder="Cari kata..."
                        class="w-full rounded-[10px] border border-[#E5E5E5] bg-[#F8FAFC] py-[11px] pl-11 pr-5 text-[15px] font-medium text-[#656565] focus:outline-none focus:ring-1 focus:ring-[#0047AB]">
                </div>
            </div>

            <div id="daftar-kata-list" class="flex flex-wrap gap-3 p-10">
                @foreach ($kataTerlarang as $kata)
                    <span class="kata-pill flex h-[42px] items-center gap-5 rounded-[17px] border-[0.5px] border-[#A19E9E] bg-[#F8FAFC] px-3.5 py-3">
                        <span class="kata-pill-label text-[15px] font-medium text-[#656565]">{{ $kata }}</span>
                        <button type="button" aria-label="Hapus kata" class="hapus-kata-btn text-[#656565] hover:text-[#D83D3D]">
                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.5 8.25V12.75M7.5 8.25V12.75M4.5 5.25V14.25C4.5 14.6478 4.65804 15.0294 4.93934 15.3107C5.22064 15.592 5.60218 15.75 6 15.75H12C12.3978 15.75 12.7794 15.592 13.0607 15.3107C13.342 15.0294 13.5 14.6478 13.5 14.25V5.25M3 5.25H15M5.25 5.25L6.75 2.25H11.25L12.75 5.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </span>
                @endforeach
            </div>
        </div>

    </div>
</main>

<script>
(function () {
    var list = document.getElementById('daftar-kata-list');
    var title = document.getElementById('daftar-kata-title');
    var input = document.getElementById('kata-baru-input');
    var addBtn = document.getElementById('tambah-kata-btn');
    var searchInput = document.getElementById('cari-kata-input');

    function updateCount() {
        title.textContent = 'Daftar Kata (' + list.querySelectorAll('.kata-pill').length + ')';
    }

    function createPill(word) {
        var pill = document.createElement('span');
        pill.className = 'kata-pill flex h-[42px] items-center gap-5 rounded-[17px] border-[0.5px] border-[#A19E9E] bg-[#F8FAFC] px-3.5 py-3';
        pill.innerHTML =
            '<span class="kata-pill-label text-[15px] font-medium text-[#656565]"></span>' +
            '<button type="button" aria-label="Hapus kata" class="hapus-kata-btn text-[#656565] hover:text-[#D83D3D]">' +
            '<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">' +
            '<path d="M10.5 8.25V12.75M7.5 8.25V12.75M4.5 5.25V14.25C4.5 14.6478 4.65804 15.0294 4.93934 15.3107C5.22064 15.592 5.60218 15.75 6 15.75H12C12.3978 15.75 12.7794 15.592 13.0607 15.3107C13.342 15.0294 13.5 14.6478 13.5 14.25V5.25M3 5.25H15M5.25 5.25L6.75 2.25H11.25L12.75 5.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>' +
            '</svg></button>';
        pill.querySelector('.kata-pill-label').textContent = word;
        return pill;
    }

    list.addEventListener('click', function (event) {
        var btn = event.target.closest('.hapus-kata-btn');
        if (!btn) return;
        btn.closest('.kata-pill').remove();
        updateCount();
    });

    addBtn.addEventListener('click', function () {
        var raw = input.value.trim();
        if (!raw) return;

        var existing = Array.from(list.querySelectorAll('.kata-pill-label')).map(function (el) {
            return el.textContent.trim().toLowerCase();
        });

        raw.split(/[,\s]+/).forEach(function (word) {
            word = word.trim();
            if (!word || existing.indexOf(word.toLowerCase()) !== -1) return;
            list.appendChild(createPill(word));
            existing.push(word.toLowerCase());
        });

        input.value = '';
        updateCount();
    });

    input.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            addBtn.click();
        }
    });

    searchInput.addEventListener('input', function () {
        var query = searchInput.value.trim().toLowerCase();
        list.querySelectorAll('.kata-pill').forEach(function (pill) {
            var word = pill.querySelector('.kata-pill-label').textContent.trim().toLowerCase();
            pill.style.display = word.indexOf(query) !== -1 ? '' : 'none';
        });
    });
})();
</script>

@include('components.footer')

</body>
</html>
