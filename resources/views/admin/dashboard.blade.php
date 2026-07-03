<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin – LurahOnline</title>
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

    <div class="mx-auto flex w-full max-w-[1283px] flex-col items-start gap-[35px] px-6 sm:px-8 lg:px-4">

        {{-- ── Page Header ── --}}
        <div class="flex items-end gap-3.5">
            <div class="flex h-[53px] w-[53px] shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[#0047AB] to-[#153655]">
                <svg width="35" height="35" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18.9583 13.125V4.375H30.625V13.125H18.9583ZM4.375 18.9583V4.375H16.0417V18.9583H4.375ZM18.9583 30.625V16.0417H30.625V30.625H18.9583ZM4.375 30.625V21.875H16.0417V30.625H4.375ZM7.29167 16.0417H13.125V7.29167H7.29167V16.0417ZM21.875 27.7083H27.7083V18.9583H21.875V27.7083ZM21.875 10.2083H27.7083V7.29167H21.875V10.2083ZM7.29167 27.7083H13.125V24.7917H7.29167V27.7083Z" fill="white"/>
                </svg>
            </div>
            <div class="flex w-[300px] flex-col items-start gap-0.5">
                <h1 class="bg-gradient-to-r from-[#0047AB] to-[#153655] bg-clip-text text-[30px] font-extrabold leading-none text-transparent">
                    Dashboard Admin
                </h1>
                <p class="w-[300px] text-[18px] font-medium text-[#464646]">Kelola pengaduan warga.</p>
            </div>
        </div>

        @php
            $statusCards = $statusCards ?? [
                ['key' => 'verifikasi', 'label' => 'Verifikasi', 'jumlah' => 1, 'active' => true],
                ['key' => 'disposisi', 'label' => 'Disposisi', 'jumlah' => 0, 'active' => false],
                ['key' => 'penanganan', 'label' => 'Penanganan', 'jumlah' => 2, 'active' => false],
                ['key' => 'selesai', 'label' => 'Selesai', 'jumlah' => 5, 'active' => false],
                ['key' => 'ditolak', 'label' => 'Ditolak', 'jumlah' => 1, 'active' => false],
            ];

            $verifikasiList = $verifikasiList ?? [
                [
                    'kode' => 'LO-260504-AG9AY',
                    'judul' => 'testing',
                    'kategori' => 'Infrastruktur Jalan',
                    'status' => 'Diajukan',
                    'foto' => 'https://api.builder.io/api/v1/image/assets/TEMP/38434f727da8a7bceb4a86eb11c33e81f26fa9b2?width=1322',
                    'lokasi' => 'Jalan Perkutut',
                    'pelapor' => 'Anonim',
                    'diajukan' => '23 Apr 2026, 16.47',
                    'deskripsi' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                ],
            ];
        @endphp

        {{-- ── Status Cards ── --}}
        <div class="grid w-full grid-cols-2 gap-5 sm:grid-cols-3 lg:grid-cols-5">
            @foreach ($statusCards as $card)
                <div class="rounded-[15px] p-[34px] {{ $card['active']
                    ? 'border border-[#0047AB] shadow-[0_0_4px_0_#0047AB]'
                    : 'border-[0.5px] border-[#A19E9E] shadow-[0_4px_10px_0_rgba(0,0,0,0.25)]' }} bg-white">
                    <p class="text-[15px] font-medium text-[#464646]">{{ $card['label'] }}</p>
                    <p class="mt-[10px] text-[18px] font-extrabold text-black">{{ $card['jumlah'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- ── Verifikasi List + Detail Panel ── --}}
        <div class="grid w-full grid-cols-1 gap-5 lg:grid-cols-[minmax(0,1fr)_minmax(0,1.4fr)]">

            {{-- Verifikasi List --}}
            <div class="rounded-[20px] border-[0.5px] border-[#A19E9E] bg-white shadow-[0_4px_10px_0_rgba(0,0,0,0.20)] overflow-hidden">
                <div class="bg-[#F9F9F9] px-6 py-5 border-b border-[#E5E5E5]">
                    <h2 class="text-[20px] font-extrabold text-[#153655]">Verifikasi ({{ count($verifikasiList) }})</h2>
                </div>

                <div class="flex flex-col divide-y divide-[#E5E5E5]">
                    @forelse ($verifikasiList as $item)
                        @php
                            $badge = match (strtolower($item['status'])) {
                                'ditolak' => 'border-[#D83D3D] bg-[#FFD9D9] text-[#D83D3D]',
                                'diajukan' => 'border-[#2E77BB] bg-[#CCF4FF] text-[#2E77BB]',
                                'selesai' => 'border-[#098A00] bg-[#C9ECC1] text-[#098A00]',
                                'diproses', 'disposisi', 'penanganan' => 'border-[#D87A00] bg-[#FFF2C7] text-[#D87A00]',
                                default => 'border-[#A19E9E] bg-[#E5E5E5] text-[#464646]',
                            };
                            $dot = match (strtolower($item['status'])) {
                                'ditolak' => 'bg-[#D83D3D]',
                                'diajukan' => 'bg-[#2E77BB]',
                                'selesai' => 'bg-[#098A00]',
                                'diproses', 'disposisi', 'penanganan' => 'bg-[#D87A00]',
                                default => 'bg-[#464646]',
                            };
                        @endphp
                        <button type="button" data-verifikasi-item="{{ $item['kode'] }}" onclick="showVerifikasiDetail('{{ $item['kode'] }}')" class="flex flex-col gap-1 px-6 py-5 text-left hover:bg-slate-50 transition-colors">
                            <div class="flex items-center justify-between gap-3">
                                <span class="font-['Cascadia_Code'] text-[15px] font-medium text-[#464646]">{{ $item['kode'] }}</span>
                                <span class="inline-flex shrink-0 items-center gap-2 rounded-full border-[0.5px] px-4 py-1 text-[13px] font-medium {{ $badge }}">
                                    <span class="h-[5px] w-[5px] rounded-full {{ $dot }}"></span>
                                    {{ $item['status'] }}
                                </span>
                            </div>
                            <p class="text-[15px] font-semibold text-black">{{ $item['judul'] }}</p>
                            <div class="flex items-center gap-[5px]">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M2.77936 2.77942V2.78182C2.73647 3.26497 2.7058 3.74913 2.68736 4.23382C2.64096 5.40182 2.64736 6.78822 2.80096 7.81782C2.8142 7.8821 2.84687 7.94075 2.89456 7.98582L9.09776 14.189C9.17277 14.264 9.2745 14.3061 9.38056 14.3061C9.48663 14.3061 9.58835 14.264 9.66336 14.189L14.189 9.66342C14.2639 9.58841 14.3061 9.48669 14.3061 9.38062C14.3061 9.27456 14.2639 9.17283 14.189 9.09782L7.98576 2.89462C7.94068 2.84694 7.88203 2.81426 7.81776 2.80102C6.78816 2.64742 5.40176 2.64102 4.23376 2.68742C3.74906 2.70586 3.26491 2.73653 2.78176 2.77942H2.77936ZM2.07216 2.07222C1.99456 2.14582 1.68096 5.72582 2.01056 7.93622C2.04576 8.17142 2.16256 8.38422 2.33056 8.55222L8.53296 14.7554C8.75799 14.9804 9.06316 15.1068 9.38136 15.1068C9.69956 15.1068 10.0047 14.9804 10.2298 14.7554L14.7554 10.2298C14.9803 10.0048 15.1067 9.69962 15.1067 9.38142C15.1067 9.06323 14.9803 8.75806 14.7554 8.53302L8.55136 2.32822C8.3851 2.15901 8.1694 2.04696 7.93536 2.00822C5.72496 1.67942 2.14496 1.99222 2.07136 2.07062" fill="#656565"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M6.06101 6.12927C6.0228 6.16616 5.99233 6.2103 5.97137 6.2591C5.9504 6.30791 5.93937 6.36039 5.93891 6.41351C5.93844 6.46662 5.94856 6.51929 5.96868 6.56845C5.98879 6.61761 6.01849 6.66227 6.05605 6.69982C6.0936 6.73738 6.13827 6.76708 6.18742 6.78719C6.23658 6.80731 6.28925 6.81743 6.34237 6.81697C6.39548 6.8165 6.44797 6.80547 6.49677 6.78451C6.54557 6.76354 6.58971 6.73307 6.62661 6.69487C6.69947 6.61942 6.73979 6.51838 6.73888 6.41351C6.73796 6.30863 6.6959 6.2083 6.62173 6.13414C6.54757 6.05998 6.44725 6.01791 6.34237 6.017C6.23749 6.01608 6.13645 6.0564 6.06101 6.12927ZM5.49621 7.26047C5.37965 7.15027 5.28637 7.01784 5.22188 6.87098C5.15739 6.72412 5.12299 6.56582 5.12073 6.40544C5.11846 6.24507 5.14836 6.08586 5.20868 5.93724C5.26899 5.78861 5.35849 5.65359 5.47188 5.54015C5.58527 5.42671 5.72025 5.33715 5.86884 5.27676C6.01744 5.21638 6.17663 5.1864 6.33701 5.18859C6.49739 5.19078 6.6557 5.2251 6.80259 5.28953C6.94948 5.35395 7.08196 5.44717 7.19221 5.56367C7.40699 5.79063 7.52476 6.09243 7.52049 6.40488C7.51622 6.71733 7.39024 7.01579 7.16933 7.2368C6.94843 7.45781 6.65002 7.58394 6.33757 7.58836C6.02512 7.59278 5.72327 7.47514 5.49621 7.26047Z" fill="#656565"/>
                                </svg>
                                <span class="text-[15px] font-medium text-[#656565]">{{ $item['kategori'] }}</span>
                            </div>
                        </button>
                    @empty
                        <p class="px-6 py-8 text-center text-[15px] font-medium text-[#A19E9E]">Tidak ada pengaduan menunggu verifikasi.</p>
                    @endforelse
                </div>
            </div>

            {{-- Detail Panel --}}
            <div>
                {{-- Empty state --}}
                <div id="verifikasi-detail-empty" class="{{ count($verifikasiList) === 0 ? '' : 'hidden' }} flex min-h-[240px] items-center justify-center rounded-[20px] border-[0.5px] border-[#A19E9E] bg-white p-10 shadow-[0_4px_10px_0_rgba(0,0,0,0.20)]">
                    <div class="flex flex-col items-center gap-3 text-center">
                        <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8.33301 16.6668H41.6663M41.6663 16.6668L33.333 8.3335H16.6663L8.33301 16.6668V37.5002C8.33301 38.6052 8.77199 39.665 9.5534 40.4464C10.3348 41.2278 11.3946 41.6668 12.4997 41.6668H37.4997C38.6047 41.6668 39.6645 41.2278 40.446 40.4464C41.2274 39.665 41.6663 38.6052 41.6663 37.5002V16.6668ZM16.6663 25.0002H24.9997" stroke="#A19E9E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <p class="max-w-[258px] text-[15px] font-medium text-[#A19E9E]">Pilih pengaduan untuk melihat detail</p>
                    </div>
                </div>

                {{-- Filled detail per item --}}
                @foreach ($verifikasiList as $index => $item)
                    <div id="verifikasi-detail-{{ $item['kode'] }}" data-verifikasi-detail class="{{ $index === 0 ? '' : 'hidden' }} rounded-[20px] border-[0.5px] border-[#A19E9E] bg-white p-9 shadow-[0_4px_10px_0_rgba(0,0,0,0.20)]">
                        <div class="flex flex-col items-start gap-5">

                            <div class="flex flex-col items-start gap-2">
                                <p class="text-[15px] font-medium text-[#656565]">{{ $item['kode'] }}</p>
                                <h2 class="text-[25px] font-extrabold text-[#153655]">{{ $item['judul'] }}</h2>
                                <span class="inline-flex items-center gap-[3px] rounded-full border-[0.5px] border-[#2E77BB] bg-[#CCF4FF] px-[15px] py-1 text-[13px] font-medium text-[#2E77BB]">
                                    <span class="h-[5px] w-[5px] rounded-full bg-[#2E77BB]"></span>
                                    {{ $item['status'] }}
                                </span>
                            </div>

                            <div class="flex w-full flex-col items-start gap-[10px]">
                                <p class="text-[15px] font-medium text-[#656565]">Foto Bukti</p>
                                <img src="{{ $item['foto'] }}" alt="Foto bukti pengaduan" class="h-[271px] w-full rounded-[20px] object-cover">
                            </div>

                            <div class="flex w-full flex-wrap items-start gap-x-[100px] gap-y-5">
                                <div class="flex flex-col items-start gap-[11px]">
                                    <div class="flex items-start gap-[7px]">
                                        <svg width="19" height="23" viewBox="0 0 19 23" fill="none" xmlns="http://www.w3.org/2000/svg" class="shrink-0">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M1.31493 5.31782V5.32126C1.2534 6.0144 1.20939 6.70898 1.18294 7.40433C1.11638 9.07997 1.12556 11.0689 1.34592 12.546C1.3649 12.6382 1.41178 12.7224 1.4802 12.787L10.3794 21.6863C10.4871 21.7939 10.633 21.8543 10.7852 21.8543C10.9373 21.8543 11.0833 21.7939 11.1909 21.6863L17.6834 15.1938C17.791 15.0861 17.8514 14.9402 17.8514 14.788C17.8514 14.6359 17.791 14.4899 17.6834 14.3823L8.78414 5.48309C8.71947 5.41467 8.63533 5.3678 8.54313 5.34881C7.06604 5.12845 5.07708 5.11927 3.40144 5.18583C2.70608 5.21228 2.01151 5.25629 1.31837 5.31782H1.31493ZM0.300364 4.30325C0.189037 4.40884 -0.26086 9.54479 0.211991 12.7159C0.26249 13.0533 0.430054 13.3586 0.67107 13.5996L9.56917 22.4988C9.892 22.8216 10.3298 23.0029 10.7863 23.0029C11.2428 23.0029 11.6806 22.8216 12.0034 22.4988L18.496 16.0063C18.8187 15.6835 19 15.2457 19 14.7892C19 14.3327 18.8187 13.8949 18.496 13.5721L9.59556 4.67052C9.35704 4.42776 9.0476 4.26701 8.71184 4.21144C5.54075 3.73974 0.404804 4.18849 0.299216 4.30096" fill="#0047AB"/>
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M6.02295 10.1231C5.96814 10.176 5.92442 10.2394 5.89435 10.3094C5.86427 10.3794 5.84844 10.4547 5.84778 10.5309C5.84712 10.6071 5.86164 10.6826 5.89049 10.7532C5.91934 10.8237 5.96195 10.8878 6.01583 10.9416C6.06971 10.9955 6.13379 11.0381 6.20431 11.067C6.27483 11.0958 6.3504 11.1104 6.42659 11.1097C6.50279 11.109 6.57809 11.0932 6.6481 11.0631C6.71811 11.0331 6.78143 10.9893 6.83437 10.9345C6.9389 10.8263 6.99674 10.6814 6.99543 10.5309C6.99413 10.3804 6.93377 10.2365 6.82738 10.1301C6.72098 10.0237 6.57705 9.96336 6.42659 9.96205C6.27613 9.96074 6.13118 10.0186 6.02295 10.1231ZM5.21267 11.746C5.04546 11.5879 4.91164 11.3979 4.81912 11.1872C4.7266 10.9765 4.67725 10.7494 4.674 10.5193C4.67075 10.2892 4.71365 10.0608 4.80018 9.84763C4.88671 9.63441 5.0151 9.4407 5.17777 9.27795C5.34044 9.11521 5.53409 8.98672 5.74726 8.90009C5.96044 8.81347 6.18882 8.77045 6.41891 8.7736C6.64899 8.77674 6.87611 8.82598 7.08684 8.91841C7.29757 9.01083 7.48763 9.14456 7.64579 9.31169C7.95392 9.6373 8.12289 10.0703 8.11676 10.5185C8.11063 10.9668 7.92989 11.3949 7.61298 11.712C7.29606 12.0291 6.86796 12.21 6.41972 12.2164C5.97147 12.2227 5.53843 12.0539 5.21267 11.746Z" fill="#0047AB"/>
                                        </svg>
                                        <div>
                                            <p class="text-[15px] font-medium text-[#656565]">Kategori</p>
                                            <p class="text-[18px] font-bold text-[#464646]">{{ $item['kategori'] }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-[7px]">
                                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" class="shrink-0">
                                            <path d="M10.9998 2.2002C9.83285 2.2002 8.71369 2.66377 7.88853 3.48893C7.06337 4.31409 6.5998 5.43324 6.5998 6.6002C6.5998 7.76715 7.06337 8.88631 7.88853 9.71147C8.71369 10.5366 9.83285 11.0002 10.9998 11.0002C12.1668 11.0002 13.2859 10.5366 14.1111 9.71147C14.9362 8.88631 15.3998 7.76715 15.3998 6.6002C15.3998 5.43324 14.9362 4.31409 14.1111 3.48893C13.2859 2.66377 12.1668 2.2002 10.9998 2.2002ZM7.6998 6.6002C7.6998 5.72498 8.04748 4.88561 8.66635 4.26674C9.28522 3.64787 10.1246 3.3002 10.9998 3.3002C11.875 3.3002 12.7144 3.64787 13.3333 4.26674C13.9521 4.88561 14.2998 5.72498 14.2998 6.6002C14.2998 7.47541 13.9521 8.31478 13.3333 8.93365C12.7144 9.55252 11.875 9.9002 10.9998 9.9002C10.1246 9.9002 9.28522 9.55252 8.66635 8.93365C8.04748 8.31478 7.6998 7.47541 7.6998 6.6002ZM5.5097 12.1002C5.21996 12.0989 4.93282 12.1548 4.66476 12.2648C4.3967 12.3748 4.153 12.5366 3.94767 12.741C3.74233 12.9455 3.57939 13.1884 3.46821 13.456C3.35703 13.7236 3.2998 14.0105 3.2998 14.3002C3.2998 16.1603 4.2161 17.5628 5.6483 18.4769C7.05851 19.3756 8.9593 19.8002 10.9998 19.8002C13.0403 19.8002 14.9411 19.3756 16.3513 18.4769C17.7835 17.5639 18.6998 16.1592 18.6998 14.3002C18.6998 13.7167 18.468 13.1571 18.0554 12.7446C17.6429 12.332 17.0833 12.1002 16.4998 12.1002H5.5097ZM4.3998 14.3002C4.3998 13.6919 4.8926 13.2002 5.5097 13.2002H16.4998C16.7915 13.2002 17.0713 13.3161 17.2776 13.5224C17.4839 13.7287 17.5998 14.0085 17.5998 14.3002C17.5998 15.7401 16.9156 16.8126 15.7595 17.5485C14.5825 18.2998 12.9083 18.7002 10.9998 18.7002C9.0913 18.7002 7.4171 18.2998 6.2401 17.5485C5.0851 16.8115 4.3998 15.7412 4.3998 14.3002Z" fill="#0047AB"/>
                                        </svg>
                                        <div>
                                            <p class="text-[15px] font-medium text-[#656565]">Pelapor</p>
                                            <p class="text-[18px] font-bold text-[#464646]">{{ $item['pelapor'] }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-col items-start gap-[11px]">
                                    <div class="flex items-start gap-[7px]">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="shrink-0">
                                            <path d="M15.625 8.125C15.625 6.63316 15.0324 5.20242 13.9775 4.14752C12.9226 3.09263 11.4918 2.5 10 2.5C8.50816 2.5 7.07742 3.09263 6.02252 4.14752C4.96763 5.20242 4.375 6.63316 4.375 8.125C4.375 10.4316 6.2207 13.4395 10 17.043C13.7793 13.4375 15.625 10.4297 15.625 8.125ZM10 18.75C5.41667 14.5833 3.125 11.0417 3.125 8.125C3.125 6.30164 3.84933 4.55295 5.13864 3.26364C6.42795 1.97433 8.17664 1.25 10 1.25C11.8234 1.25 13.572 1.97433 14.8614 3.26364C16.1507 4.55295 16.875 6.30164 16.875 8.125C16.875 11.0417 14.5833 14.5833 10 18.75Z" fill="#0047AB"/>
                                            <path d="M10 10C10.4973 10 10.9742 9.80246 11.3258 9.45083C11.6775 9.09919 11.875 8.62228 11.875 8.125C11.875 7.62772 11.6775 7.15081 11.3258 6.79917C10.9742 6.44754 10.4973 6.25 10 6.25C9.50272 6.25 9.02581 6.44754 8.67417 6.79917C8.32254 7.15081 8.125 7.62772 8.125 8.125C8.125 8.62228 8.32254 9.09919 8.67417 9.45083C9.02581 9.80246 9.50272 10 10 10ZM10 11.25C9.1712 11.25 8.37634 10.9208 7.79029 10.3347C7.20424 9.74866 6.875 8.9538 6.875 8.125C6.875 7.2962 7.20424 6.50134 7.79029 5.91529C8.37634 5.32924 9.1712 5 10 5C10.8288 5 11.6237 5.32924 12.2097 5.91529C12.7958 6.50134 13.125 7.2962 13.125 8.125C13.125 8.9538 12.7958 9.74866 12.2097 10.3347C11.6237 10.9208 10.8288 11.25 10 11.25Z" fill="#0047AB"/>
                                        </svg>
                                        <div>
                                            <p class="text-[15px] font-medium text-[#656565]">Lokasi</p>
                                            <p class="text-[18px] font-bold text-[#464646]">{{ $item['lokasi'] }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-[7px]">
                                        <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg" class="shrink-0">
                                            <path d="M7.29167 2.0835H8.33333C8.6096 2.0835 8.87455 2.19324 9.0699 2.38859C9.26525 2.58394 9.375 2.8489 9.375 3.12516V4.16683H14.5833V3.12516C14.5833 2.8489 14.6931 2.58394 14.8884 2.38859C15.0838 2.19324 15.3487 2.0835 15.625 2.0835H16.6667C16.9429 2.0835 17.2079 2.19324 17.4032 2.38859C17.5986 2.58394 17.7083 2.8489 17.7083 3.12516V4.16683C18.5371 4.16683 19.332 4.49607 19.918 5.08212C20.5041 5.66817 20.8333 6.46303 20.8333 7.29183V18.7502C20.8333 19.579 20.5041 20.3738 19.918 20.9599C19.332 21.5459 18.5371 21.8752 17.7083 21.8752H6.25C5.4212 21.8752 4.62634 21.5459 4.04029 20.9599C3.45424 20.3738 3.125 19.579 3.125 18.7502V7.29183C3.125 6.46303 3.45424 5.66817 4.04029 5.08212C4.62634 4.49607 5.4212 4.16683 6.25 4.16683V3.12516C6.25 2.8489 6.35975 2.58394 6.5551 2.38859C6.75045 2.19324 7.0154 2.0835 7.29167 2.0835ZM15.625 4.16683H16.6667V3.12516H15.625V4.16683ZM8.33333 4.16683V3.12516H7.29167V4.16683H8.33333ZM6.25 5.2085C5.69747 5.2085 5.16756 5.42799 4.77686 5.81869C4.38616 6.20939 4.16667 6.7393 4.16667 7.29183V8.3335H19.7917V7.29183C19.7917 6.7393 19.5722 6.20939 19.1815 5.81869C18.7908 5.42799 18.2609 5.2085 17.7083 5.2085H6.25ZM4.16667 18.7502C4.16667 19.3027 4.38616 19.8326 4.77686 20.2233C5.16756 20.614 5.69747 20.8335 6.25 20.8335H17.7083C18.2609 20.8335 18.7908 20.614 19.1815 20.2233C19.5722 19.8326 19.7917 19.3027 19.7917 18.7502V9.37516H4.16667V18.7502ZM12.5 13.5418H17.7083V18.7502H12.5V13.5418ZM13.5417 14.5835V17.7085H16.6667V14.5835H13.5417Z" fill="#0047AB"/>
                                        </svg>
                                        <div>
                                            <p class="text-[15px] font-medium text-[#656565]">Diajukan</p>
                                            <p class="text-[18px] font-bold text-[#464646]">{{ $item['diajukan'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex max-w-[486px] flex-col items-start gap-2">
                                <p class="text-[15px] font-medium text-[#656565]">Deskripsi</p>
                                <p class="text-[18px] font-bold text-[#464646]">{{ $item['deskripsi'] }}</p>
                            </div>

                            <div class="w-full rounded-[20px] border-[0.5px] border-[#A19E9E] bg-[#F8FAFC] px-9 py-8">
                                <form class="flex flex-col items-start gap-[15px]">
                                    <div class="flex flex-col items-start gap-[5px]">
                                        <h3 class="text-[18px] font-bold text-[#464646]">Verifikasi Pengaduan</h3>
                                        <label for="alasan-{{ $item['kode'] }}" class="text-[15px] font-medium text-[#464646]">Alasan penolakan (jika ditolak)</label>
                                    </div>
                                    <textarea id="alasan-{{ $item['kode'] }}" rows="3" placeholder="Contoh: Foto tidak jelas, lokasi di luar wilayah..." class="w-full rounded-[15px] border-[0.5px] border-[#656565] px-[23px] py-[18px] text-[15px] font-medium text-[#464646] placeholder:text-[#A19E9E] focus:outline-none focus:ring-1 focus:ring-[#0047AB]"></textarea>
                                    <div class="flex w-full items-center gap-[23px]">
                                        <button type="submit" class="flex items-center justify-center gap-[10px] rounded-[10px] bg-[#098A00] px-[91px] py-[6px] text-[15px] font-bold text-white">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22C17.5 22 22 17.5 22 12C22 6.5 17.5 2 12 2ZM12 20C7.59 20 4 16.41 4 12C4 7.59 7.59 4 12 4C16.41 4 20 7.59 20 12C20 16.41 16.41 20 12 20ZM16.59 7.58L10 14.17L7.41 11.59L6 13L10 17L18 9L16.59 7.58Z" fill="white"/>
                                            </svg>
                                            Verifikasi
                                        </button>
                                        <button type="button" class="flex w-[283px] items-center justify-center gap-[10px] rounded-[10px] bg-[#D83D3D] py-[6px] text-[15px] font-bold text-white">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M8.4 17L12 13.4L15.6 17L17 15.6L13.4 12L17 8.4L15.6 7L12 10.6L8.4 7L7 8.4L10.6 12L7 15.6L8.4 17ZM12 22C10.6167 22 9.31667 21.7373 8.1 21.212C6.88334 20.6867 5.825 19.9743 4.925 19.075C4.025 18.1757 3.31267 17.1173 2.788 15.9C2.26333 14.6827 2.00067 13.3827 2 12C1.99933 10.6173 2.262 9.31733 2.788 8.1C3.314 6.88267 4.02633 5.82433 4.925 4.925C5.82367 4.02567 6.882 3.31333 8.1 2.788C9.318 2.26267 10.618 2 12 2C13.382 2 14.682 2.26267 15.9 2.788C17.118 3.31333 18.1763 4.02567 19.075 4.925C19.9737 5.82433 20.6863 6.88267 21.213 8.1C21.7397 9.31733 22.002 10.6173 22 12C21.998 13.3827 21.7353 14.6827 21.212 15.9C20.6887 17.1173 19.9763 18.1757 19.075 19.075C18.1737 19.9743 17.1153 20.687 15.9 21.213C14.6847 21.739 13.3847 22.0013 12 22ZM12 20C14.2333 20 16.125 19.225 17.675 17.675C19.225 16.125 20 14.2333 20 12C20 9.76667 19.225 7.875 17.675 6.325C16.125 4.775 14.2333 4 12 4C9.76667 4 7.875 4.775 6.325 6.325C4.775 7.875 4 9.76667 4 12C4 14.2333 4.775 16.125 6.325 17.675C7.875 19.225 9.76667 20 12 20Z" fill="white"/>
                                            </svg>
                                            Tolak</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</main>

<script>
function showVerifikasiDetail(kode) {
    document.getElementById('verifikasi-detail-empty')?.classList.add('hidden');
    document.querySelectorAll('[data-verifikasi-detail]').forEach(function (el) {
        el.classList.toggle('hidden', el.id !== 'verifikasi-detail-' + kode);
    });
}
</script>

@include('components.footer')

</body>
</html>
