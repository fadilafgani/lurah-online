<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Admin – LurahOnline</title>
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

    <div class="mx-auto flex w-full max-w-[1283px] flex-col items-center gap-[30px] px-6 sm:px-8 lg:px-4">

        {{-- ── Page Header ── --}}
        <div class="flex w-full flex-wrap items-end justify-between gap-5">
            <div class="flex items-end gap-3.5">
                <div class="flex h-[53px] w-[53px] shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[#0047AB] to-[#153655]">
                    <svg width="35" height="35" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.375 32.5837V12.167H10.2083V32.5837H4.375ZM14.5833 32.5837V3.41699H20.4167V32.5837H14.5833ZM24.7917 32.5837V20.917H30.625V32.5837H24.7917Z" fill="white"/>
                    </svg>
                </div>
                <div class="flex w-[300px] flex-col items-start gap-0.5">
                    <h1 class="bg-gradient-to-r from-[#0047AB] to-[#153655] bg-clip-text text-[30px] font-extrabold leading-none text-transparent">
                        Laporan
                    </h1>
                    <p class="w-[300px] text-[18px] font-medium text-[#464646]">Statistik &amp; ekspor data pengaduan</p>
                </div>
            </div>

            <button type="button" id="ekspor-csv-btn" class="flex items-center gap-[5px] rounded-[15px] bg-gradient-to-r from-[#0047AB] to-[#153655] px-5 py-[7px] shadow-[2px_2px_4px_0_rgba(0,0,0,0.25)]">
                <svg width="24" height="24" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16.0002 21.333L9.3335 14.6663L11.2002 12.733L14.6668 16.1997V5.33301H17.3335V16.1997L20.8002 12.733L22.6668 14.6663L16.0002 21.333ZM8.00016 26.6663C7.26683 26.6663 6.63927 26.4055 6.1175 25.8837C5.59572 25.3619 5.33438 24.7339 5.3335 23.9997V19.9997H8.00016V23.9997H24.0002V19.9997H26.6668V23.9997C26.6668 24.733 26.4059 25.361 25.8842 25.8837C25.3624 26.4063 24.7344 26.6672 24.0002 26.6663H8.00016Z" fill="white"/>
                </svg>
                <span class="text-[20px] font-semibold text-white">Ekspor CSV</span>
            </button>
        </div>

        @php
            $periode = $periode ?? '30 Hari Terakhir';
            $statusFilter = $statusFilter ?? 'Semua Status';

            $stats = $stats ?? [
                ['label' => 'Total Pengaduan', 'value' => 7, 'sub' => null, 'subColor' => null, 'bg' => '#E5E5E5', 'icon' => 'note'],
                ['label' => 'Sedang diproses', 'value' => 2, 'sub' => null, 'subColor' => null, 'bg' => '#FFF2C7', 'icon' => 'clock'],
                ['label' => 'Selesai', 'value' => 5, 'sub' => '70% tuntas', 'subColor' => '#098A00', 'bg' => '#C9ECC1', 'icon' => 'check'],
                ['label' => 'Ditolak', 'value' => 1, 'sub' => null, 'subColor' => null, 'bg' => '#FFE4E4', 'icon' => 'cancel'],
                ['label' => 'Rata-Rata Rating', 'value' => '4.8', 'sub' => '5 Ulasan', 'subColor' => '#2E77BB', 'bg' => '#CCF4FF', 'icon' => 'star'],
            ];

            $kategoris = $kategoris ?? [
                ['nama' => 'Infrastruktur Jalan', 'jumlah' => 4, 'pct' => 100],
                ['nama' => 'Administrasi Kependudukan', 'jumlah' => 2, 'pct' => 54],
                ['nama' => 'Saluran Air / Drainase', 'jumlah' => 1, 'pct' => 14],
            ];

            $unitLoads = $unitLoads ?? [
                ['nama' => 'Unit Infrastruktur', 'jumlah' => 4, 'pct' => 100],
                ['nama' => 'Unit Administrasi', 'jumlah' => 2, 'pct' => 54],
                ['nama' => 'Unit Kebersihan', 'jumlah' => 1, 'pct' => 14],
            ];

            $pengaduans = $pengaduans ?? [
                ['kode' => 'LO-260423-RSJ1', 'tanggal' => '23/4/2026', 'judul' => 'Jalan Rusak', 'kategori' => 'Infrastruktur Jalan', 'status' => 'Ditolak', 'unit' => 'Infrastruktur', 'rating' => null],
                ['kode' => 'LO-260420-MKNN', 'tanggal' => '23/4/2026', 'judul' => 'Pelayanan lama', 'kategori' => 'Administrasi Kependudukan', 'status' => 'Diajukan', 'unit' => 'Administrasi', 'rating' => null],
                ['kode' => 'LO-260417-HNCR', 'tanggal' => '22/4/2026', 'judul' => 'jalan rusak lagi', 'kategori' => 'Infrastruktur Jalan', 'status' => 'Selesai', 'unit' => 'Infrastruktur', 'rating' => null],
                ['kode' => 'LO-260417-SFIA', 'tanggal' => '21/4/2026', 'judul' => 'Jalan Rusak dan Bergelombang', 'kategori' => 'Infrastruktur Jalan', 'status' => 'Selesai', 'unit' => 'Infrastruktur', 'rating' => 4],
                ['kode' => 'LO-260423-MHRA', 'tanggal' => '20/4/2026', 'judul' => 'Saluran air tersumbat', 'kategori' => 'Saluran Air / Drainase', 'status' => 'Diproses', 'unit' => 'Kebersihan', 'rating' => null],
                ['kode' => 'LO-260417-SMNH', 'tanggal' => '19/4/2026', 'judul' => 'mati listrik', 'kategori' => 'Infrastruktur Jalan', 'status' => 'Selesai', 'unit' => 'Infrastruktur', 'rating' => 5],
                ['kode' => 'LO-260428-RPL1', 'tanggal' => '16/4/2026', 'judul' => 'Pelayanan Kurang', 'kategori' => 'Administrasi Kependudukan', 'status' => 'Selesai', 'unit' => 'Administrasi', 'rating' => 4],
            ];
        @endphp

        {{-- ── Filters ── --}}
        <div class="w-full rounded-[20px] border-[0.5px] border-[#A19E9E] bg-white p-6 shadow-[0_4px_10px_0_rgba(0,0,0,0.25)] sm:p-8">
            <div class="flex flex-col gap-6 md:flex-row md:items-end">
                <div class="flex w-full flex-col items-start gap-[10px]">
                    <label for="filter-periode" class="text-[15px] font-semibold text-[#464646]">Periode</label>
                    <select id="filter-periode" class="w-full appearance-none rounded-[10px] border-[0.5px] border-[#A19E9E] bg-[#F9F9F9] px-[23px] py-[7px] text-[15px] font-medium text-[#464646] focus:outline-none focus:ring-1 focus:ring-[#0047AB]" style="background-image:url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'%3E%3Cpath d='M8 10L12 14L16 10' stroke='%23464646' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E&quot;);background-repeat:no-repeat;background-position:right 23px center">
                        <option {{ $periode === '7 Hari Terakhir' ? 'selected' : '' }}>7 Hari Terakhir</option>
                        <option {{ $periode === '30 Hari Terakhir' ? 'selected' : '' }}>30 Hari Terakhir</option>
                        <option {{ $periode === '90 Hari Terakhir' ? 'selected' : '' }}>90 Hari Terakhir</option>
                        <option {{ $periode === 'Semua Waktu' ? 'selected' : '' }}>Semua Waktu</option>
                    </select>
                </div>
                <div class="flex w-full flex-col items-start gap-[10px]">
                    <label for="filter-status" class="text-[15px] font-semibold text-[#464646]">Status</label>
                    <select id="filter-status" class="w-full appearance-none rounded-[10px] border-[0.5px] border-[#A19E9E] bg-[#F9F9F9] px-[26px] py-[7px] text-[15px] font-medium text-[#464646] focus:outline-none focus:ring-1 focus:ring-[#0047AB]" style="background-image:url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'%3E%3Cpath d='M8 10L12 14L16 10' stroke='%23464646' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E&quot;);background-repeat:no-repeat;background-position:right 26px center">
                        <option {{ $statusFilter === 'Semua Status' ? 'selected' : '' }}>Semua Status</option>
                        <option {{ $statusFilter === 'Diajukan' ? 'selected' : '' }}>Diajukan</option>
                        <option {{ $statusFilter === 'Diproses' ? 'selected' : '' }}>Diproses</option>
                        <option {{ $statusFilter === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        <option {{ $statusFilter === 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- ── Stat Cards ── --}}
        <div class="grid w-full grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-5 lg:gap-[27px]">
            @foreach ($stats as $stat)
                <div class="relative rounded-[15px] border-[0.5px] border-[#A19E9E] bg-white p-5 shadow-[0_4px_10px_0_rgba(0,0,0,0.25)]">
                    <div class="absolute right-[13px] top-[13px] flex h-[30px] w-[30px] items-center justify-center rounded-full" style="background:{{ $stat['bg'] }}">
                        @switch($stat['icon'])
                            @case('note')
                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6.1875 6.75C6.1875 6.60082 6.24676 6.45774 6.35225 6.35225C6.45774 6.24676 6.60082 6.1875 6.75 6.1875H11.25C11.3992 6.1875 11.5423 6.24676 11.6477 6.35225C11.7532 6.45774 11.8125 6.60082 11.8125 6.75C11.8125 6.89918 11.7532 7.04226 11.6477 7.14775C11.5423 7.25324 11.3992 7.3125 11.25 7.3125H6.75C6.60082 7.3125 6.45774 7.25324 6.35225 7.14775C6.24676 7.04226 6.1875 6.89918 6.1875 6.75ZM6.75 9.5625H11.25C11.3992 9.5625 11.5423 9.50324 11.6477 9.39775C11.7532 9.29226 11.8125 9.14918 11.8125 9C11.8125 8.85082 11.7532 8.70774 11.6477 8.60225C11.5423 8.49676 11.3992 8.4375 11.25 8.4375H6.75C6.60082 8.4375 6.45774 8.49676 6.35225 8.60225C6.24676 8.70774 6.1875 8.85082 6.1875 9C6.1875 9.14918 6.24676 9.29226 6.35225 9.39775C6.45774 9.50324 6.60082 9.5625 6.75 9.5625ZM9 10.6875H6.75C6.60082 10.6875 6.45774 10.7468 6.35225 10.8523C6.24676 10.9577 6.1875 11.1008 6.1875 11.25C6.1875 11.3992 6.24676 11.5423 6.35225 11.6477C6.45774 11.7532 6.60082 11.8125 6.75 11.8125H9C9.14918 11.8125 9.29226 11.7532 9.39775 11.6477C9.50324 11.5423 9.5625 11.3992 9.5625 11.25C9.5625 11.1008 9.50324 10.9577 9.39775 10.8523C9.29226 10.7468 9.14918 10.6875 9 10.6875ZM15.75 3.375V11.0173C15.7505 11.1651 15.7216 11.3115 15.665 11.448C15.6083 11.5845 15.5252 11.7084 15.4202 11.8125L11.8125 15.4202C11.7084 15.5252 11.5845 15.6083 11.448 15.665C11.3115 15.7216 11.1651 15.7505 11.0173 15.75H3.375C3.07663 15.75 2.79048 15.6315 2.5795 15.4205C2.36853 15.2095 2.25 14.9234 2.25 14.625V3.375C2.25 3.07663 2.36853 2.79048 2.5795 2.5795C2.79048 2.36853 3.07663 2.25 3.375 2.25H14.625C14.9234 2.25 15.2095 2.36853 15.4205 2.5795C15.6315 2.79048 15.75 3.07663 15.75 3.375ZM3.375 14.625H10.6875V11.25C10.6875 11.1008 10.7468 10.9577 10.8523 10.8523C10.9577 10.7468 11.1008 10.6875 11.25 10.6875H14.625V3.375H3.375V14.625ZM11.8125 11.8125V13.8305L13.8298 11.8125H11.8125Z" fill="#464646"/>
                                </svg>
                                @break
                            @case('clock')
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.99984 1.66699C14.6023 1.66699 18.3332 5.39783 18.3332 10.0003C18.3332 14.6028 14.6023 18.3337 9.99984 18.3337C5.39734 18.3337 1.6665 14.6028 1.6665 10.0003C1.6665 5.39783 5.39734 1.66699 9.99984 1.66699ZM9.99984 3.33366C8.23173 3.33366 6.53603 4.03604 5.28579 5.28628C4.03555 6.53652 3.33317 8.23222 3.33317 10.0003C3.33317 11.7684 4.03555 13.4641 5.28579 14.7144C6.53603 15.9646 8.23173 16.667 9.99984 16.667C11.7679 16.667 13.4636 15.9646 14.7139 14.7144C15.9641 13.4641 16.6665 11.7684 16.6665 10.0003C16.6665 8.23222 15.9641 6.53652 14.7139 5.28628C13.4636 4.03604 11.7679 3.33366 9.99984 3.33366ZM9.99984 5.00033C10.2039 5.00035 10.401 5.07529 10.5535 5.21092C10.706 5.34655 10.8035 5.53345 10.8273 5.73616L10.8332 5.83366V9.65533L13.089 11.9112C13.2385 12.0611 13.3252 12.2624 13.3317 12.474C13.3382 12.6856 13.2638 12.8918 13.1238 13.0506C12.9838 13.2094 12.7885 13.3089 12.5778 13.329C12.367 13.3491 12.1565 13.2882 11.989 13.1587L11.9107 13.0895L9.41067 10.5895C9.28115 10.4599 9.19797 10.2912 9.174 10.1095L9.1665 10.0003V5.83366C9.1665 5.61264 9.2543 5.40068 9.41058 5.2444C9.56686 5.08812 9.77882 5.00033 9.99984 5.00033Z" fill="#FFC400"/>
                                </svg>
                                @break
                            @case('check')
                                <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M11.0002 1.83301C5.9585 1.83301 1.8335 5.95801 1.8335 10.9997C1.8335 16.0413 5.9585 20.1663 11.0002 20.1663C16.0418 20.1663 20.1668 16.0413 20.1668 10.9997C20.1668 5.95801 16.0418 1.83301 11.0002 1.83301ZM11.0002 18.333C6.95766 18.333 3.66683 15.0422 3.66683 10.9997C3.66683 6.95717 6.95766 3.66634 11.0002 3.66634C15.0427 3.66634 18.3335 6.95717 18.3335 10.9997C18.3335 15.0422 15.0427 18.333 11.0002 18.333ZM15.2077 6.94801L9.16683 12.9888L6.79266 10.6238L5.50016 11.9163L9.16683 15.583L16.5002 8.24967L15.2077 6.94801Z" fill="#0CB800"/>
                                </svg>
                                @break
                            @case('cancel')
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.4 17L12 13.4L15.6 17L17 15.6L13.4 12L17 8.4L15.6 7L12 10.6L8.4 7L7 8.4L10.6 12L7 15.6L8.4 17ZM12 22C10.6167 22 9.31667 21.7373 8.1 21.212C6.88334 20.6867 5.825 19.9743 4.925 19.075C4.025 18.1757 3.31267 17.1173 2.788 15.9C2.26333 14.6827 2.00067 13.3827 2 12C1.99933 10.6173 2.262 9.31733 2.788 8.1C3.314 6.88267 4.02633 5.82433 4.925 4.925C5.82367 4.02567 6.882 3.31333 8.1 2.788C9.318 2.26267 10.618 2 12 2C13.382 2 14.682 2.26267 15.9 2.788C17.118 3.31333 18.1763 4.02567 19.075 4.925C19.9737 5.82433 20.6863 6.88267 21.213 8.1C21.7397 9.31733 22.002 10.6173 22 12C21.998 13.3827 21.7353 14.6827 21.212 15.9C20.6887 17.1173 19.9763 18.1757 19.075 19.075C18.1737 19.9743 17.1153 20.687 15.9 21.213C14.6847 21.739 13.3847 22.0013 12 22ZM12 20C14.2333 20 16.125 19.225 17.675 17.675C19.225 16.125 20 14.2333 20 12C20 9.76667 19.225 7.875 17.675 6.325C16.125 4.775 14.2333 4 12 4C9.76667 4 7.875 4.775 6.325 6.325C4.775 7.875 4 9.76667 4 12C4 14.2333 4.775 16.125 6.325 17.675C7.875 19.225 9.76667 20 12 20Z" fill="#D83D3D"/>
                                </svg>
                                @break
                            @case('star')
                                <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10.3251 19.629L14.0001 17.4123L17.6751 19.6581L16.7126 15.4581L19.9501 12.6581L15.6918 12.279L14.0001 8.31231L12.3085 12.2498L8.05013 12.629L11.2876 15.4581L10.3251 19.629ZM14.0001 18.7855L9.76513 21.3428C9.64069 21.4043 9.52519 21.4291 9.41863 21.4175C9.31285 21.405 9.2098 21.3685 9.10946 21.3078C9.00835 21.2456 8.93213 21.1577 8.8808 21.0441C8.82946 20.9306 8.8248 20.8065 8.8668 20.672L9.9938 15.877L6.2663 12.6453C6.1613 12.5598 6.09207 12.4575 6.05863 12.3385C6.02519 12.2195 6.03257 12.1055 6.0808 11.9966C6.12902 11.8878 6.19319 11.7983 6.2733 11.7283C6.35419 11.6606 6.46308 11.6148 6.59996 11.5906L11.5186 11.1613L13.4366 6.62065C13.4895 6.49231 13.5657 6.39976 13.6653 6.34298C13.7649 6.2862 13.8765 6.25781 14.0001 6.25781C14.1238 6.25781 14.2358 6.2862 14.3361 6.34298C14.4365 6.39976 14.5123 6.49231 14.5636 6.62065L16.4816 11.1613L21.3991 11.5906C21.5368 11.614 21.6461 11.6603 21.727 11.7295C21.8079 11.7979 21.8724 11.887 21.9206 11.9966C21.9681 12.1055 21.9751 12.2195 21.9416 12.3385C21.9082 12.4575 21.839 12.5598 21.734 12.6453L18.0065 15.877L19.1335 20.672C19.177 20.805 19.1727 20.9286 19.1206 21.043C19.0685 21.1573 18.9919 21.2452 18.8908 21.3066C18.7912 21.3689 18.6882 21.4058 18.5816 21.4175C18.4759 21.4291 18.3607 21.4043 18.2363 21.3428L14.0001 18.7855Z" fill="#0083A7"/>
                                </svg>
                                @break
                        @endswitch
                    </div>
                    <p class="pr-10 text-[15px] font-medium text-[#464646]">{{ $stat['label'] }}</p>
                    <p class="mt-3 text-lg font-extrabold text-black">{{ $stat['value'] }}</p>
                    @if ($stat['sub'])
                        <p class="mt-1 text-[15px] font-medium" style="color:{{ $stat['subColor'] }}">{{ $stat['sub'] }}</p>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- ── Kategori Pengaduan & Beban per Unit ── --}}
        <div class="grid w-full grid-cols-1 gap-6 lg:grid-cols-2 lg:gap-[49px]">
            <div class="rounded-[20px] border-[0.5px] border-[#A19E9E] bg-white p-6 shadow-[0_4px_10px_0_rgba(0,0,0,0.20)] sm:p-8">
                <p class="text-lg font-extrabold text-[#153655] sm:text-xl">Kategori Pengaduan</p>
                <div class="mt-6 flex flex-col gap-6">
                    @foreach ($kategoris as $kat)
                        <div>
                            <div class="mb-1.5 flex items-center justify-between">
                                <span class="text-[15px] font-medium text-[#464646]">{{ $kat['nama'] }}</span>
                                <span class="text-[15px] font-semibold text-[#153655]">{{ $kat['jumlah'] }}</span>
                            </div>
                            <div class="h-2.5 w-full rounded-full bg-gradient-to-r from-white to-[#E5E5E5]">
                                <div class="h-2.5 rounded-full bg-gradient-to-r from-[#153655] to-[#0047AB]" style="width: {{ $kat['pct'] }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-[20px] border-[0.5px] border-[#A19E9E] bg-white p-6 shadow-[0_4px_10px_0_rgba(0,0,0,0.20)] sm:p-8">
                <p class="text-lg font-extrabold text-[#153655] sm:text-xl">Beban per Unit</p>
                <div class="mt-6 flex flex-col gap-6">
                    @foreach ($unitLoads as $unit)
                        <div>
                            <div class="mb-1.5 flex items-center justify-between">
                                <span class="text-[15px] font-medium text-[#464646]">{{ $unit['nama'] }}</span>
                                <span class="text-[15px] font-semibold text-[#153655]">{{ $unit['jumlah'] }}</span>
                            </div>
                            <div class="h-2.5 w-full rounded-full bg-gradient-to-r from-white to-[#E5E5E5]">
                                <div class="h-2.5 rounded-full bg-gradient-to-r from-[#153655] to-[#0047AB]" style="width: {{ $unit['pct'] }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ── Daftar Pengaduan ── --}}
        <div class="w-full rounded-[20px] border-[0.5px] border-[#A19E9E] bg-white p-6 shadow-[0_4px_10px_0_rgba(0,0,0,0.25)] sm:p-8 lg:p-10">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <h2 class="flex items-center gap-3 text-lg font-extrabold text-[#153655] sm:text-xl">
                    <svg width="28" height="28" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.33334 12.0002V9.3335H28V12.0002H9.33334ZM9.33334 17.3335V14.6668H28V17.3335H9.33334ZM9.33334 22.6668V20.0002H28V22.6668H9.33334ZM5.33334 12.0002C4.95556 12.0002 4.63912 11.8722 4.384 11.6162C4.12889 11.3602 4.00089 11.0437 4 10.6668C3.99912 10.2899 4.12712 9.9735 4.384 9.7175C4.64089 9.4615 4.95734 9.3335 5.33334 9.3335C5.70934 9.3335 6.02623 9.4615 6.284 9.7175C6.54178 9.9735 6.66934 10.2899 6.66667 10.6668C6.664 11.0437 6.536 11.3606 6.28267 11.6175C6.02934 11.8744 5.71289 12.0019 5.33334 12.0002ZM5.33334 17.3335C4.95556 17.3335 4.63912 17.2055 4.384 16.9495C4.12889 16.6935 4.00089 16.3771 4 16.0002C3.99912 15.6233 4.12712 15.3068 4.384 15.0508C4.64089 14.7948 4.95734 14.6668 5.33334 14.6668C5.70934 14.6668 6.02623 14.7948 6.284 15.0508C6.54178 15.3068 6.66934 15.6233 6.66667 16.0002C6.664 16.3771 6.536 16.6939 6.28267 16.9508C6.02934 17.2077 5.71289 17.3353 5.33334 17.3335ZM5.33334 22.6668C4.95556 22.6668 4.63912 22.5388 4.384 22.2828C4.12889 22.0268 4.00089 21.7104 4 21.3335C3.99912 20.9566 4.12712 20.6402 4.384 20.3842C4.64089 20.1282 4.95734 20.0002 5.33334 20.0002C5.70934 20.0002 6.02623 20.1282 6.284 20.3842C6.54178 20.6402 6.66934 20.9566 6.66667 21.3335C6.664 21.7104 6.536 22.0273 6.28267 22.2842C6.02934 22.5411 5.71289 22.6686 5.33334 22.6668Z" fill="#153655"/>
                    </svg>
                    Daftar Pengaduan
                </h2>
                <span class="text-lg font-medium text-[#464646]">{{ count($pengaduans) }} Pengaduan</span>
            </div>

            <div class="mt-9 overflow-x-auto">
                <table id="pengaduan-table" class="w-full min-w-[900px] border-collapse text-left">
                    <thead>
                        <tr class="text-[18px] font-medium text-[#464646]">
                            <th class="pb-4 font-medium">Kode Tiket</th>
                            <th class="pb-4 font-medium">Tanggal</th>
                            <th class="pb-4 font-medium">Judul</th>
                            <th class="pb-4 font-medium">Kategori</th>
                            <th class="pb-4 font-medium">Status</th>
                            <th class="pb-4 font-medium">Unit</th>
                            <th class="pb-4 font-medium">Rating</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengaduans as $p)
                            @php
                                $badge = match (strtolower($p['status'])) {
                                    'ditolak' => 'border-[#D83D3D] bg-[#FFD9D9] text-[#D83D3D]',
                                    'diajukan' => 'border-[#2E77BB] bg-[#CCF4FF] text-[#2E77BB]',
                                    'selesai' => 'border-[#098A00] bg-[#C9ECC1] text-[#098A00]',
                                    'diproses' => 'border-[#D87A00] bg-[#FFF2C7] text-[#D87A00]',
                                    default => 'border-[#A19E9E] bg-[#E5E5E5] text-[#464646]',
                                };
                                $dot = match (strtolower($p['status'])) {
                                    'ditolak' => 'bg-[#D83D3D]',
                                    'diajukan' => 'bg-[#2E77BB]',
                                    'selesai' => 'bg-[#098A00]',
                                    'diproses' => 'bg-[#D87A00]',
                                    default => 'bg-[#464646]',
                                };
                            @endphp
                            <tr class="border-t-[0.5px] border-[#A19E9E]">
                                <td class="py-5 pr-4 text-[15px] font-medium text-black">{{ $p['kode'] }}</td>
                                <td class="py-5 pr-4 text-[15px] font-medium text-[#464646]">{{ $p['tanggal'] }}</td>
                                <td class="py-5 pr-4 text-[15px] font-medium text-black">{{ $p['judul'] }}</td>
                                <td class="py-5 pr-4 text-[15px] font-medium text-[#464646]">{{ $p['kategori'] }}</td>
                                <td class="py-5 pr-4">
                                    <span class="inline-flex items-center gap-[7px] rounded-full border-[0.5px] px-5 py-1.5 text-[15px] font-medium {{ $badge }}">
                                        <span class="h-[6px] w-[6px] rounded-full {{ $dot }}"></span>
                                        {{ $p['status'] }}
                                    </span>
                                </td>
                                <td class="py-5 pr-4 text-[15px] font-medium text-[#464646]">{{ $p['unit'] }}</td>
                                <td class="py-5 text-[15px] font-medium text-[#464646]">{{ $p['rating'] ? $p['rating'] . '★' : '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>

<script>
document.getElementById('ekspor-csv-btn')?.addEventListener('click', function () {
    var table = document.getElementById('pengaduan-table');
    var rows = Array.from(table.querySelectorAll('tr')).map(function (row) {
        return Array.from(row.querySelectorAll('th, td')).map(function (cell) {
            return '"' + cell.textContent.trim().replace(/"/g, '""') + '"';
        }).join(',');
    });

    var csv = rows.join('\n');
    var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    var url = URL.createObjectURL(blob);
    var link = document.createElement('a');
    link.href = url;
    link.download = 'laporan-pengaduan.csv';
    link.click();
    URL.revokeObjectURL(url);
});
</script>

@include('components.footer')

</body>
</html>
