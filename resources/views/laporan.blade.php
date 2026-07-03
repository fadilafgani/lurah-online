<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Publik – LurahOnline</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-white text-slate-900 antialiased">

@include('components.navbar')

<main class="relative overflow-hidden pt-[126px] pb-16">

    {{-- ── Ambient background blobs ── --}}
    <div aria-hidden="true" class="pointer-events-none absolute inset-x-0 top-[271px] -z-10 flex justify-between blur-[350px]">
        <div class="-ml-24 h-[280px] w-[280px] shrink-0 rounded-full bg-[#0047AB]"></div>
        <div class="-mr-24 h-[220px] w-[460px] shrink-0 rounded-full bg-[#00B4D8] opacity-60"></div>
    </div>

    <div class="mx-auto flex w-full max-w-[1283px] flex-col items-center gap-8 px-6 sm:px-8 md:gap-11 lg:px-4">

        {{-- ── Page Header ── --}}
        <div class="flex w-full items-end gap-3.5">
            <div class="flex h-[53px] w-[53px] shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[#0047AB] to-[#153655]">
                <svg width="35" height="35" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.375 32.5832V12.1665H10.2083V32.5832H4.375ZM14.5833 32.5832V3.4165H20.4167V32.5832H14.5833ZM24.7917 32.5832V20.9165H30.625V32.5832H24.7917Z" fill="white"/>
                </svg>
            </div>
            <div class="flex flex-col items-start gap-0.5">
                <h1 class="bg-gradient-to-r from-[#0047AB] to-[#153655] bg-clip-text text-[26px] font-extrabold leading-none text-transparent sm:text-[30px]">
                    Laporan Publik
                </h1>
                <p class="text-base font-medium text-[#464646] sm:text-lg">Transparasi data pengaduan warga</p>
            </div>
        </div>

        {{-- ── Stat Cards ── --}}
        <div class="grid w-full grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 lg:gap-[49px]">

            {{-- Total --}}
            <div class="relative rounded-[15px] border-[0.5px] border-[#A19E9E] bg-white p-5 shadow-[0_4px_10px_0_rgba(0,0,0,0.25)]">
                <div class="absolute right-5 top-5 flex h-[30px] w-[30px] items-center justify-center rounded-full bg-[#E5E5E5]">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6.1875 6.75C6.1875 6.60082 6.24676 6.45774 6.35225 6.35225C6.45774 6.24676 6.60082 6.1875 6.75 6.1875H11.25C11.3992 6.1875 11.5423 6.24676 11.6477 6.35225C11.7532 6.45774 11.8125 6.60082 11.8125 6.75C11.8125 6.89918 11.7532 7.04226 11.6477 7.14775C11.5423 7.25324 11.3992 7.3125 11.25 7.3125H6.75C6.60082 7.3125 6.45774 7.25324 6.35225 7.14775C6.24676 7.04226 6.1875 6.89918 6.1875 6.75ZM6.75 9.5625H11.25C11.3992 9.5625 11.5423 9.50324 11.6477 9.39775C11.7532 9.29226 11.8125 9.14918 11.8125 9C11.8125 8.85082 11.7532 8.70774 11.6477 8.60225C11.5423 8.49676 11.3992 8.4375 11.25 8.4375H6.75C6.60082 8.4375 6.45774 8.49676 6.35225 8.60225C6.24676 8.70774 6.1875 8.85082 6.1875 9C6.1875 9.14918 6.24676 9.29226 6.35225 9.39775C6.45774 9.50324 6.60082 9.5625 6.75 9.5625ZM9 10.6875H6.75C6.60082 10.6875 6.45774 10.7468 6.35225 10.8523C6.24676 10.9577 6.1875 11.1008 6.1875 11.25C6.1875 11.3992 6.24676 11.5423 6.35225 11.6477C6.45774 11.7532 6.60082 11.8125 6.75 11.8125H9C9.14918 11.8125 9.29226 11.7532 9.39775 11.6477C9.50324 11.5423 9.5625 11.3992 9.5625 11.25C9.5625 11.1008 9.50324 10.9577 9.39775 10.8523C9.29226 10.7468 9.14918 10.6875 9 10.6875ZM15.75 3.375V11.0173C15.7505 11.1651 15.7216 11.3115 15.665 11.448C15.6083 11.5845 15.5252 11.7084 15.4202 11.8125L11.8125 15.4202C11.7084 15.5252 11.5845 15.6083 11.448 15.665C11.3115 15.7216 11.1651 15.7505 11.0173 15.75H3.375C3.07663 15.75 2.79048 15.6315 2.5795 15.4205C2.36853 15.2095 2.25 14.9234 2.25 14.625V3.375C2.25 3.07663 2.36853 2.79048 2.5795 2.5795C2.79048 2.36853 3.07663 2.25 3.375 2.25H14.625C14.9234 2.25 15.2095 2.36853 15.4205 2.5795C15.6315 2.79048 15.75 3.07663 15.75 3.375ZM3.375 14.625H10.6875V11.25C10.6875 11.1008 10.7468 10.9577 10.8523 10.8523C10.9577 10.7468 11.1008 10.6875 11.25 10.6875H14.625V3.375H3.375V14.625ZM11.8125 11.8125V13.8305L13.8298 11.8125H11.8125Z" fill="#464646"/>
                    </svg>
                </div>
                <p class="pr-10 text-[15px] font-medium text-[#464646]">Total Pengaduan</p>
                <p class="mt-3 text-lg font-extrabold text-black">{{ $totalPengaduan ?? 7 }}</p>
            </div>

            {{-- Diproses --}}
            <div class="relative rounded-[15px] border-[0.5px] border-[#A19E9E] bg-white p-5 shadow-[0_4px_10px_0_rgba(0,0,0,0.25)]">
                <div class="absolute right-5 top-5 flex h-[30px] w-[30px] items-center justify-center rounded-full bg-[#FFF2C7]">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.99996 1.6665C14.6025 1.6665 18.3333 5.39734 18.3333 9.99984C18.3333 14.6023 14.6025 18.3332 9.99996 18.3332C5.39746 18.3332 1.66663 14.6023 1.66663 9.99984C1.66663 5.39734 5.39746 1.6665 9.99996 1.6665ZM9.99996 3.33317C8.23185 3.33317 6.53616 4.03555 5.28591 5.28579C4.03567 6.53603 3.33329 8.23173 3.33329 9.99984C3.33329 11.7679 4.03567 13.4636 5.28591 14.7139C6.53616 15.9641 8.23185 16.6665 9.99996 16.6665C11.7681 16.6665 13.4638 15.9641 14.714 14.7139C15.9642 13.4636 16.6666 11.7679 16.6666 9.99984C16.6666 8.23173 15.9642 6.53603 14.714 5.28579C13.4638 4.03555 11.7681 3.33317 9.99996 3.33317ZM9.99996 4.99984C10.2041 4.99986 10.4011 5.0748 10.5536 5.21043C10.7061 5.34607 10.8036 5.53296 10.8275 5.73567L10.8333 5.83317V9.65484L13.0891 11.9107C13.2386 12.0606 13.3254 12.2619 13.3318 12.4735C13.3383 12.6851 13.2639 12.8913 13.1239 13.0501C12.9839 13.2089 12.7887 13.3084 12.5779 13.3285C12.3671 13.3486 12.1566 13.2877 11.9891 13.1582L11.9108 13.089L9.41079 10.589C9.28128 10.4594 9.19809 10.2907 9.17413 10.109L9.16663 9.99984V5.83317C9.16663 5.61216 9.25442 5.4002 9.4107 5.24392C9.56698 5.08763 9.77894 4.99984 9.99996 4.99984Z" fill="#FFC400"/>
                    </svg>
                </div>
                <p class="pr-10 text-[15px] font-medium text-[#464646]">Sedang diproses</p>
                <p class="mt-3 text-lg font-extrabold text-black">{{ $sedangDiproses ?? 2 }}</p>
            </div>

            {{-- Selesai --}}
            <div class="relative rounded-[15px] border-[0.5px] border-[#A19E9E] bg-white p-5 shadow-[0_4px_10px_0_rgba(0,0,0,0.25)]">
                <div class="absolute right-5 top-5 flex h-[30px] w-[30px] items-center justify-center rounded-full bg-[#C9ECC1]">
                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11 1.8335C5.95837 1.8335 1.83337 5.9585 1.83337 11.0002C1.83337 16.0418 5.95837 20.1668 11 20.1668C16.0417 20.1668 20.1667 16.0418 20.1667 11.0002C20.1667 5.9585 16.0417 1.8335 11 1.8335ZM11 18.3335C6.95754 18.3335 3.66671 15.0427 3.66671 11.0002C3.66671 6.95766 6.95754 3.66683 11 3.66683C15.0425 3.66683 18.3334 6.95766 18.3334 11.0002C18.3334 15.0427 15.0425 18.3335 11 18.3335ZM15.2075 6.9485L9.16671 12.9893L6.79254 10.6243L5.50004 11.9168L9.16671 15.5835L16.5 8.25016L15.2075 6.9485Z" fill="#0CB800"/>
                    </svg>
                </div>
                <p class="pr-10 text-[15px] font-medium text-[#464646]">Selesai</p>
                <p class="mt-3 text-lg font-extrabold text-black">{{ $selesai ?? 5 }}</p>
                <p class="mt-1 text-[15px] font-medium text-[#098A00]">{{ $pctSelesai ?? 70 }}% tuntas</p>
            </div>

            {{-- Rating --}}
            <div class="relative rounded-[15px] border-[0.5px] border-[#A19E9E] bg-white p-5 shadow-[0_4px_10px_0_rgba(0,0,0,0.25)]">
                <div class="absolute right-5 top-5 flex h-[30px] w-[30px] items-center justify-center rounded-full bg-[#CCF4FF]">
                    <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.3249 19.629L13.9999 17.4123L17.6749 19.6581L16.7124 15.4581L19.9499 12.6581L15.6916 12.279L13.9999 8.31231L12.3082 12.2498L8.04989 12.629L11.2874 15.4581L10.3249 19.629ZM13.9999 18.7855L9.76489 21.3428C9.64044 21.4043 9.52494 21.4291 9.41839 21.4175C9.31261 21.405 9.20955 21.3685 9.10922 21.3078C9.00811 21.2456 8.93189 21.1577 8.88055 21.0441C8.82922 20.9306 8.82455 20.8065 8.86655 20.672L9.99355 15.877L6.26605 12.6453C6.16105 12.5598 6.09183 12.4575 6.05839 12.3385C6.02494 12.2195 6.03233 12.1055 6.08055 11.9966C6.12878 11.8878 6.19294 11.7983 6.27305 11.7283C6.35394 11.6606 6.46283 11.6148 6.59972 11.5906L11.5184 11.1613L13.4364 6.62065C13.4893 6.49231 13.5655 6.39976 13.6651 6.34298C13.7646 6.2862 13.8762 6.25781 13.9999 6.25781C14.1236 6.25781 14.2356 6.2862 14.3359 6.34298C14.4362 6.39976 14.5121 6.49231 14.5634 6.62065L16.4814 11.1613L21.3989 11.5906C21.5366 11.614 21.6458 11.6603 21.7267 11.7295C21.8076 11.7979 21.8722 11.887 21.9204 11.9966C21.9678 12.1055 21.9748 12.2195 21.9414 12.3385C21.9079 12.4575 21.8387 12.5598 21.7337 12.6453L18.0062 15.877L19.1332 20.672C19.1768 20.805 19.1725 20.9286 19.1204 21.043C19.0683 21.1573 18.9917 21.2452 18.8906 21.3066C18.791 21.3689 18.6879 21.4058 18.5814 21.4175C18.4756 21.4291 18.3605 21.4043 18.2361 21.3428L13.9999 18.7855Z" fill="#0083A7"/>
                    </svg>
                </div>
                <p class="pr-10 text-[15px] font-medium text-[#464646]">Rata-Rata Rating</p>
                <p class="mt-3 text-lg font-extrabold text-black">{{ number_format($rataRating ?? 4.8, 1) }}</p>
                <p class="mt-1 text-[15px] font-medium text-[#2E77BB]">{{ $jumlahUlasan ?? 5 }} Ulasan</p>
            </div>

        </div>

        {{-- ── Mid Row ── --}}
        <div class="grid w-full grid-cols-1 gap-6 lg:grid-cols-2 lg:gap-[49px]">

            {{-- Trend Chart --}}
            <div class="rounded-[20px] border-[0.5px] border-[#A19E9E] bg-white p-6 shadow-[0_4px_10px_0_rgba(0,0,0,0.20)] sm:p-8">
                <p class="flex items-center gap-2 text-lg font-extrabold text-[#153655] sm:text-xl">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16 11.78L20.24 4.45L21.97 5.45L16.74 14.5L10.23 10.75L5.46 19H22V21H2V3H4V17.54L9.5 8L16 11.78Z" fill="#153655"/>
                    </svg>
                    Tren 6 Bulan Terakhir
                </p>
                <div class="relative mt-16">
                    <span class="absolute bottom-3 left-1/2 -translate-x-1/2 text-lg font-semibold text-[#153655]">{{ $trendPeak ?? 14 }}</span>
                    <div class="h-0.5 w-full rounded-full bg-gradient-to-r from-[#153655] to-[#0047AB]"></div>
                    <p class="mt-3 text-center text-[15px] font-medium text-[#464646]">{{ $trendLabel ?? 'Apr 26' }}</p>
                </div>
            </div>

            {{-- Kategori --}}
            <div class="rounded-[20px] border-[0.5px] border-[#A19E9E] bg-white p-6 shadow-[0_4px_10px_0_rgba(0,0,0,0.20)] sm:p-8">
                <p class="text-lg font-extrabold text-[#153655] sm:text-xl">Kategori Pengaduan</p>
                @php
                    $kategoris = $kategoris ?? [
                        ['nama' => 'Infrastruktur Jalan',       'jumlah' => 4, 'pct' => 100],
                        ['nama' => 'Administrasi Kependudukan', 'jumlah' => 2, 'pct' => 54],
                        ['nama' => 'Saluran Air / Drainase',    'jumlah' => 1, 'pct' => 14],
                    ];
                @endphp
                <div class="mt-6 flex flex-col gap-6">
                    @foreach($kategoris as $kat)
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

        </div>

        {{-- ── Daftar Laporan ── --}}
        <div class="flex w-full flex-col items-center gap-[27px]">
            <div class="w-full rounded-[20px] border-[0.5px] border-[#A19E9E] bg-white p-6 shadow-[0_4px_10px_0_rgba(0,0,0,0.25)] sm:p-8 lg:p-10">

                <div class="flex flex-wrap items-center justify-between gap-3">
                    <h2 class="flex items-center gap-3 text-lg font-extrabold text-[#153655] sm:text-xl">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.33334 12.0002V9.3335H28V12.0002H9.33334ZM9.33334 17.3335V14.6668H28V17.3335H9.33334ZM9.33334 22.6668V20.0002H28V22.6668H9.33334ZM5.33334 12.0002C4.95556 12.0002 4.63912 11.8722 4.384 11.6162C4.12889 11.3602 4.00089 11.0437 4 10.6668C3.99912 10.2899 4.12712 9.9735 4.384 9.7175C4.64089 9.4615 4.95734 9.3335 5.33334 9.3335C5.70934 9.3335 6.02623 9.4615 6.284 9.7175C6.54178 9.9735 6.66934 10.2899 6.66667 10.6668C6.664 11.0437 6.536 11.3606 6.28267 11.6175C6.02934 11.8744 5.71289 12.0019 5.33334 12.0002ZM5.33334 17.3335C4.95556 17.3335 4.63912 17.2055 4.384 16.9495C4.12889 16.6935 4.00089 16.3771 4 16.0002C3.99912 15.6233 4.12712 15.3068 4.384 15.0508C4.64089 14.7948 4.95734 14.6668 5.33334 14.6668C5.70934 14.6668 6.02623 14.7948 6.284 15.0508C6.54178 15.3068 6.66934 15.6233 6.66667 16.0002C6.664 16.3771 6.536 16.6939 6.28267 16.9508C6.02934 17.2077 5.71289 17.3353 5.33334 17.3335ZM5.33334 22.6668C4.95556 22.6668 4.63912 22.5388 4.384 22.2828C4.12889 22.0268 4.00089 21.7104 4 21.3335C3.99912 20.9566 4.12712 20.6402 4.384 20.3842C4.64089 20.1282 4.95734 20.0002 5.33334 20.0002C5.70934 20.0002 6.02623 20.1282 6.284 20.3842C6.54178 20.6402 6.66934 20.9566 6.66667 21.3335C6.664 21.7104 6.536 22.0273 6.28267 22.2842C6.02934 22.5411 5.71289 22.6686 5.33334 22.6668Z" fill="#153655"/>
                        </svg>
                        Daftar Laporan
                    </h2>
                    <span class="text-lg font-medium text-[#464646]">{{ count($laporans ?? []) ?: 7 }} Laporan</span>
                </div>

                @php
                $laporans = $laporans ?? [
                    ['kode'=>'LO-260423-*****','judul'=>'Jalan Rusak',                'kategori'=>'Infrastruktur Jalan',          'status'=>'Ditolak'],
                    ['kode'=>'LO-260420-*****','judul'=>'Pelayanan lama',              'kategori'=>'Administrasi Kependudukan',     'status'=>'Diajukan'],
                    ['kode'=>'LO-260417-*****','judul'=>'jalan rusak lagi',            'kategori'=>'Infrastruktur Jalan',           'status'=>'Selesai'],
                    ['kode'=>'LO-260417-*****','judul'=>'Jalan Rusak dan Bergelombang','kategori'=>'Infrastruktur Jalan',           'status'=>'Selesai'],
                    ['kode'=>'LO-260423-*****','judul'=>'Saluran air tersumbat',       'kategori'=>'Saluran Air / Drainase',        'status'=>'Diproses'],
                    ['kode'=>'LO-260417-*****','judul'=>'mati listrik',                'kategori'=>'Infrastruktur Jalan',           'status'=>'Selesai'],
                    ['kode'=>'LO-260428-*****','judul'=>'Pelayanan Kurang',            'kategori'=>'Administrasi Kependudukan',     'status'=>'Selesai'],
                ];
                @endphp

                <div class="mt-9 overflow-x-auto">
                    <table class="w-full min-w-[720px] border-collapse text-left">
                        <thead>
                            <tr class="text-lg font-medium text-[#464646]">
                                <th class="pb-4 font-medium">Kode Tiket</th>
                                <th class="pb-4 font-medium">Judul</th>
                                <th class="pb-4 font-medium">Kategori</th>
                                <th class="pb-4 font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($laporans as $lap)
                            @php
                                $badge = match(strtolower($lap['status'])) {
                                    'ditolak'  => 'border-[#D83D3D] bg-[#FFD9D9] text-[#D83D3D]',
                                    'diajukan' => 'border-[#2E77BB] bg-[#CCF4FF] text-[#2E77BB]',
                                    'selesai'  => 'border-[#098A00] bg-[#C9ECC1] text-[#098A00]',
                                    'diproses' => 'border-[#D87A00] bg-[#FFF2C7] text-[#D87A00]',
                                    default    => 'border-[#A19E9E] bg-[#E5E5E5] text-[#464646]',
                                };
                                $dot = match(strtolower($lap['status'])) {
                                    'ditolak'  => 'bg-[#D83D3D]',
                                    'diajukan' => 'bg-[#2E77BB]',
                                    'selesai'  => 'bg-[#098A00]',
                                    'diproses' => 'bg-[#D87A00]',
                                    default    => 'bg-[#464646]',
                                };
                            @endphp
                            <tr class="border-t-[0.5px] border-[#A19E9E]">
                                <td class="py-5 pr-4 text-[17px] font-medium text-black">{{ $lap['kode'] }}</td>
                                <td class="py-5 pr-4 text-[17px] font-medium text-black">{{ $lap['judul'] }}</td>
                                <td class="py-5 pr-4 text-[17px] font-medium text-black">{{ $lap['kategori'] }}</td>
                                <td class="py-5">
                                    <span class="inline-flex items-center gap-2 rounded-full border-[0.5px] px-5 py-1.5 text-[17px] font-medium {{ $badge }}">
                                        <span class="h-2 w-2 rounded-full {{ $dot }}"></span>
                                        {{ $lap['status'] }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <p class="w-full text-center text-[17px] font-medium text-[#464646]">
                Data diperbarui secara realtime berdasarkan pengaduan yang masuk.
            </p>
        </div>

    </div>
</main>

@include('components.footer')

</body>
</html>
