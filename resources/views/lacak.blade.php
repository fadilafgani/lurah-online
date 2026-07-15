<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Lurah Online</title>

        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
        </style>
    </head>
    <body class="min-h-screen flex flex-col bg-white text-slate-900 antialiased">
        @include('components.navbar')
        <main class="relative overflow-hidden flex-1 pt-24 px-4 pb-16 flex items-start justify-center"
        style="background: linear-gradient(135deg,#e8eef7 0%,#dce6f5 50%,#e4eaf6 100%);">

            <svg class="pointer-events-none absolute bottom-[-120px] left-1/2 -translate-x-1/2 blur-[120px] z-0" width="1495" height="384" viewBox="0 0 1495 384" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M298 192C298 298.039 231.29 384 149 384C66.7096 384 0 298.039 0 192C0 85.9613 66.7096 0 149 0C231.29 0 298 85.9613 298 192Z" fill="#0047AB"/>
                <path d="M1495 192C1495 250.542 1421.35 298 1330.5 298C1239.65 298 1166 250.542 1166 192C1166 133.458 1239.65 86 1330.5 86C1421.35 86 1495 133.458 1495 192Z" fill="#00B4D8" fill-opacity="0.6"/>
            </svg>

            <div class="relative z-10 w-full max-w-5xl mx-auto text-center">
                <h1 class="text-5xl font-extrabold leading-[1.3] bg-gradient-to-r from-[#0047AB] to-[#153655] bg-clip-text text-transparent mb-3">
                    Lacak Pengaduan
                </h1>

                <p class="text-xl text-[#464646] mb-8">
                    Masukkan kode tiket Anda untuk melihat status terkini.
                </p>

                {{-- FORM LACAK --}}
                <form action="{{ route('lacak.cari') }}" method="GET" class="mt-8">
                    <div class="flex items-center {{ request('kode') ? 'bg-[#EAF0FB]' : 'bg-white' }} rounded-full shadow-[0_8px_24px_rgba(0,0,0,0.12)] pl-8 pr-2 py-2 max-w-4xl mx-auto transition-colors">

                        <svg class="text-[#F4B400] shrink-0" width="24" height="24"
                            viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="M21 21l-4.35-4.35"/>
                        </svg>

                        <input
                            type="text"
                            name="kode"
                            value="{{ old('kode', request('kode')) }}"
                            placeholder="Contoh: LO-12345-AB789"
                            autocomplete="off"
                            class="flex-1 bg-transparent border-0 outline-none focus:ring-0 px-5 text-lg placeholder:text-gray-400 {{ request('kode') ? 'font-semibold text-[#153655]' : '' }}"
                        />

                        <button
                            type="submit"
                            class="bg-gradient-to-r from-[#0047AB] to-[#0D2E63] text-white font-semibold rounded-full px-10 py-4 text-lg hover:opacity-90 transition"
                        >
                            Lacak
                        </button>

                    </div>
                </form>

                {{-- HASIL TIKET DITEMUKAN --}}
                @isset($tiket)
                @php
                    $statusMeta = [
                        'submitted' => ['label' => 'Diajukan',     'desc' => 'Laporan Anda sudah diterima dan menunggu verifikasi.', 'dot' => 'bg-blue-500',    'pill' => 'bg-blue-50 text-blue-700'],
                        'verified'  => ['label' => 'Diverifikasi', 'desc' => 'Laporan Anda sedang diverifikasi oleh petugas.',        'dot' => 'bg-blue-500',    'pill' => 'bg-blue-50 text-blue-700'],
                        'process'   => ['label' => 'Diproses',     'desc' => 'Laporan Anda sedang diproses oleh unit terkait.',       'dot' => 'bg-amber-500',   'pill' => 'bg-amber-50 text-amber-700'],
                        'completed' => ['label' => 'Selesai',      'desc' => 'Pengaduan telah selesai ditangani.',                    'dot' => 'bg-emerald-500', 'pill' => 'bg-emerald-50 text-emerald-700'],
                        'rejected'  => ['label' => 'Ditolak',      'desc' => 'Mohon maaf, laporan Anda tidak dapat kami proses.',     'dot' => 'bg-rose-500',    'pill' => 'bg-rose-50 text-rose-700'],
                    ];
                    $meta = $statusMeta[$tiket->status] ?? $statusMeta['submitted'];
                @endphp

                {{-- RINGKASAN TIKET --}}
                <div class="mt-8 bg-white rounded-[20px] border-[0.5px] border-[#E5E5E5] shadow-[0_0_4px_rgba(0,0,0,0.25)] px-6 py-7 md:px-[50px] md:py-[34px] text-left">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-[#656565] text-base font-medium font-['Cascadia_Code']">{{ $tiket->ticket_code }}</p>
                            <p class="text-[#153655] text-[26px] font-extrabold mt-1">{{ $tiket->title }}</p>
                        </div>
                        <span class="shrink-0 inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-sm font-semibold {{ $meta['pill'] }}">
                            <span class="w-2 h-2 rounded-full {{ $meta['dot'] }}"></span>
                            {{ $meta['label'] }}
                        </span>
                    </div>
                    <p class="text-[#656565] text-lg mt-3">{{ $meta['desc'] }}</p>
                </div>

                {{-- DETAIL LAPORAN --}}
                <div class="mt-8 bg-white rounded-[20px] border-[0.5px] border-[#E5E5E5] shadow-[0_0_4px_rgba(0,0,0,0.25)] px-6 py-8 md:px-[50px] md:py-[41px] text-left">
                    <div class="flex flex-col md:flex-row gap-10">
                        @if($tiket->photo)
                        <div class="md:w-[45%] shrink-0">
                            <img src="{{ asset('storage/' . $tiket->photo) }}" alt="Foto laporan" class="w-full h-[234px] object-cover rounded-[20px]">
                        </div>
                        @endif

                        <div class="flex-1 flex flex-col gap-5">
                            <div class="flex items-start gap-[7px]">
                                <svg class="shrink-0 mt-1" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M1.72 5.79822V5.80179C1.62813 6.44165 1.5992 7.07529 1.5769 7.71054C1.51256 9.30525 1.52161 11.1815 1.72939 12.5905C1.74707 12.6746 1.79135 12.7513 1.85589 12.8113L10.2427 21.1981C10.3439 21.2993 10.4808 21.3562 10.6236 21.3562C10.7663 21.3562 10.9032 21.2993 11.0044 21.1981L17.1332 15.0693C17.2344 14.9681 17.2913 14.8312 17.2913 14.6884C17.2913 14.5457 17.2344 14.4088 17.1332 14.3076L8.75652 5.93097C8.6963 5.86622 8.6169 5.82284 8.53002 5.80714C7.13437 5.60019 5.24773 5.5917 3.67014 5.65432C3.01596 5.6791 2.35935 5.72046 1.7 5.77916L1.72 5.79822ZM0.75 4.83488C0.646 4.9328 0.216 9.7448 0.663 12.7203C0.71075 13.0345 0.868198 13.3212 1.10783 13.5478L9.53583 21.9758C9.83598 22.2758 10.2437 22.4444 10.6689 22.4444C11.0942 22.4444 11.5018 22.2758 11.802 21.9758L17.9308 15.847C18.2309 15.5469 18.3994 15.1392 18.3994 14.714C18.3994 14.2888 18.2309 13.8811 17.9308 13.581L9.50276 5.15291C9.27817 4.9241 8.98895 4.76863 8.67352 4.70694C5.68276 4.26178 0.849 4.68584 0.75 4.79218" fill="#0047AB"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M6.1416 10.4176C6.09024 10.4678 6.04943 10.5278 6.02159 10.594C5.99376 10.6602 5.97946 10.7313 5.97951 10.8032C5.97957 10.875 5.99399 10.9461 6.02194 11.0122C6.04988 11.0783 6.09079 11.1382 6.14224 11.1884C6.19369 11.2385 6.25464 11.278 6.32148 11.3044C6.38832 11.3308 6.45972 11.3437 6.53157 11.3423C6.60342 11.341 6.6743 11.3255 6.74011 11.2967C6.80591 11.2678 6.86534 11.2261 6.91491 11.174C7.01394 11.0715 7.06874 10.9345 7.06749 10.7923C7.06624 10.6501 7.00905 10.5141 6.90824 10.4133C6.80744 10.3125 6.67144 10.2553 6.52923 10.254C6.38702 10.2528 6.24999 10.3076 6.1476 10.4066" fill="#0047AB"/>
                                </svg>
                                <div class="flex flex-col gap-1">
                                    <p class="text-[#656565] text-[20px] font-medium">Kategori</p>
                                    <p class="text-[#464646] text-[20px] font-bold">{{ $tiket->category }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-[7px]">
                                <svg class="shrink-0 mt-1" width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M11 2.2002C9.8331 2.2002 8.71394 2.66377 7.88878 3.48893C7.06362 4.31409 6.60005 5.43324 6.60005 6.6002C6.60005 7.76715 7.06362 8.88631 7.88878 9.71147C8.71394 10.5366 9.8331 11.0002 11 11.0002C12.167 11.0002 13.2862 10.5366 14.1113 9.71147C14.9365 8.88631 15.4 7.76715 15.4 6.6002C15.4 5.43324 14.9365 4.31409 14.1113 3.48893C13.2862 2.66377 12.167 2.2002 11 2.2002ZM7.70005 6.6002C7.70005 5.72498 8.04773 4.88561 8.6666 4.26674C9.28547 3.64787 10.1248 3.3002 11 3.3002C11.8753 3.3002 12.7146 3.64787 13.3335 4.26674C13.9524 4.88561 14.3 5.72498 14.3 6.6002C14.3 7.47541 13.9524 8.31478 13.3335 8.93365C12.7146 9.55252 11.8753 9.9002 11 9.9002C10.1248 9.9002 9.28547 9.55252 8.6666 8.93365C8.04773 8.31478 7.70005 7.47541 7.70005 6.6002ZM5.50995 12.1002C5.22021 12.0989 4.93306 12.1548 4.665 12.2648C4.39694 12.3748 4.15325 12.5366 3.94791 12.741C3.74257 12.9455 3.57963 13.1884 3.46846 13.456C3.35728 13.7236 3.30005 14.0105 3.30005 14.3002C3.30005 16.1603 4.21635 17.5628 5.64855 18.4769C7.05875 19.3756 8.95955 19.8002 11 19.8002C13.0405 19.8002 14.9413 19.3756 16.3515 18.4769C17.7837 17.5639 18.7 16.1592 18.7 14.3002C18.7 13.7167 18.4683 13.1571 18.0557 12.7446C17.6431 12.332 17.0835 12.1002 16.5 12.1002H5.50995ZM4.40005 14.3002C4.40005 13.6919 4.89285 13.2002 5.50995 13.2002H16.5C16.7918 13.2002 17.0716 13.3161 17.2779 13.5224C17.4842 13.7287 17.6 14.0085 17.6 14.3002C17.6 15.7401 16.9158 16.8126 15.7597 17.5485C14.5827 18.2998 12.9085 18.7002 11 18.7002C9.09155 18.7002 7.41735 18.2998 6.24035 17.5485C5.08535 16.8115 4.40005 15.7412 4.40005 14.3002Z" fill="#0047AB"/>
                                </svg>
                                <div class="flex flex-col gap-1">
                                    <p class="text-[#656565] text-[20px] font-medium">Pelapor</p>
                                    <p class="text-[#464646] text-[20px] font-bold">{{ $tiket->name ?: 'Anonim' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-[7px]">
                                <svg class="shrink-0 mt-1" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15.625 8.125C15.625 6.63316 15.0324 5.20242 13.9775 4.14752C12.9226 3.09263 11.4918 2.5 10 2.5C8.50816 2.5 7.07742 3.09263 6.02252 4.14752C4.96763 5.20242 4.375 6.63316 4.375 8.125C4.375 10.4316 6.2207 13.4395 10 17.043C13.7793 13.4375 15.625 10.4297 15.625 8.125ZM10 18.75C5.41667 14.5833 3.125 11.0417 3.125 8.125C3.125 6.30164 3.84933 4.55295 5.13864 3.26364C6.42795 1.97433 8.17664 1.25 10 1.25C11.8234 1.25 13.572 1.97433 14.8614 3.26364C16.1507 4.55295 16.875 6.30164 16.875 8.125C16.875 11.0417 14.5833 14.5833 10 18.75Z" fill="#0047AB"/>
                                    <path d="M10 10C10.4973 10 10.9742 9.80246 11.3258 9.45083C11.6775 9.09919 11.875 8.62228 11.875 8.125C11.875 7.62772 11.6775 7.15081 11.3258 6.79917C10.9742 6.44754 10.4973 6.25 10 6.25C9.50272 6.25 9.02581 6.44754 8.67417 6.79917C8.32254 7.15081 8.125 7.62772 8.125 8.125C8.125 8.62228 8.32254 9.09919 8.67417 9.45083C9.02581 9.80246 9.50272 10 10 10ZM10 11.25C9.1712 11.25 8.37634 10.9208 7.79029 10.3347C7.20424 9.74866 6.875 8.9538 6.875 8.125C6.875 7.2962 7.20424 6.50134 7.79029 5.91529C8.37634 5.32924 9.1712 5 10 5C10.8288 5 11.6237 5.32924 12.2097 5.91529C12.7958 6.50134 13.125 7.2962 13.125 8.125C13.125 8.9538 12.7958 9.74866 12.2097 10.3347C11.6237 10.9208 10.8288 11.25 10 11.25Z" fill="#0047AB"/>
                                </svg>
                                <div class="flex flex-col gap-1">
                                    <p class="text-[#656565] text-[20px] font-medium">Lokasi</p>
                                    <p class="text-[#464646] text-[20px] font-bold">{{ $tiket->location }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-[7px]">
                                <svg class="shrink-0 mt-1" width="22" height="22" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7.29167 2.0835H8.33333C8.6096 2.0835 8.87455 2.19324 9.0699 2.38859C9.26525 2.58394 9.375 2.8489 9.375 3.12516V4.16683H14.5833V3.12516C14.5833 2.8489 14.6931 2.58394 14.8884 2.38859C15.0838 2.19324 15.3487 2.0835 15.625 2.0835H16.6667C16.9429 2.0835 17.2079 2.19324 17.4032 2.38859C17.5986 2.58394 17.7083 2.8489 17.7083 3.12516V4.16683C18.5371 4.16683 19.332 4.49607 19.918 5.08212C20.5041 5.66817 20.8333 6.46303 20.8333 7.29183V18.7502C20.8333 19.579 20.5041 20.3738 19.918 20.9599C19.332 21.5459 18.5371 21.8752 17.7083 21.8752H6.25C5.4212 21.8752 4.62634 21.5459 4.04029 20.9599C3.45424 20.3738 3.125 19.579 3.125 18.7502V7.29183C3.125 6.46303 3.45424 5.66817 4.04029 5.08212C4.62634 4.49607 5.4212 4.16683 6.25 4.16683V3.12516C6.25 2.8489 6.35975 2.58394 6.5551 2.38859C6.75045 2.19324 7.0154 2.0835 7.29167 2.0835ZM15.625 4.16683H16.6667V3.12516H15.625V4.16683ZM8.33333 4.16683V3.12516H7.29167V4.16683H8.33333ZM6.25 5.2085C5.69747 5.2085 5.16756 5.42799 4.77686 5.81869C4.38616 6.20939 4.16667 6.7393 4.16667 7.29183V8.3335H19.7917V7.29183C19.7917 6.7393 19.5722 6.20939 19.1815 5.81869C18.7908 5.42799 18.2609 5.2085 17.7083 5.2085H6.25ZM4.16667 18.7502C4.16667 19.3027 4.38616 19.8326 4.77686 20.2233C5.16756 20.614 5.69747 20.8335 6.25 20.8335H17.7083C18.2609 20.8335 18.7908 20.614 19.1815 20.2233C19.5722 19.8326 19.7917 19.3027 19.7917 18.7502V9.37516H4.16667V18.7502ZM12.5 13.5418H17.7083V18.7502H12.5V13.5418ZM13.5417 14.5835V17.7085H16.6667V14.5835H13.5417Z" fill="#0047AB"/>
                                </svg>
                                <div class="flex flex-col gap-1">
                                    <p class="text-[#656565] text-[20px] font-medium">Diajukan</p>
                                    <p class="text-[#464646] text-[20px] font-bold">{{ \Carbon\Carbon::parse($tiket->created_at)->translatedFormat('d M Y, H.i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-full flex flex-col items-start gap-1 mt-8 text-left">
                        <p class="text-[#656565] text-[20px] font-medium text-left">Deskripsi</p>
                        <p class="text-[#464646] text-[20px] font-bold text-left">{{ $tiket->description }}</p>
                    </div>
                </div>

                {{-- TINDAK LANJUT --}}
                @if(in_array($tiket->status, ['process', 'completed']) && $tiket->unit)
                <div class="mt-8 flex flex-col md:flex-row gap-8 items-stretch">
                    <div class="flex-1 bg-white rounded-[20px] border-[0.5px] border-[#E5E5E5] shadow-[0_0_4px_rgba(0,0,0,0.25)] px-6 py-8 md:px-[52px] md:py-9 text-left">
                        <h2 class="text-[22px] font-extrabold text-[#153655] mb-4">Tindak Lanjut</h2>
                        <div class="flex flex-col gap-5">
                            <div class="flex flex-col gap-1">
                                <p class="text-[#656565] text-[20px] font-medium">Ditangani oleh</p>
                                <p class="text-[#464646] text-[20px] font-bold">{{ $tiket->unit }}</p>
                            </div>
                            @if($tiket->handling_note)
                            <div class="flex flex-col gap-1">
                                <p class="text-[#656565] text-[20px] font-medium">Catatan</p>
                                <p class="text-[#464646] text-[20px] font-bold">{{ $tiket->handling_note }}</p>
                            </div>
                            @endif

                            {{-- Ringkasan/tanggapan akhir dari petugas.
                                 Ganti $tiket->final_note dengan nama kolom Anda jika berbeda. --}}
                            @if($tiket->final_note ?? $tiket->handling_note ?? false)
                            <div class="rounded-[16px] bg-[#EAF6F2] border border-[#CFEBE1] px-5 py-4">
                                <p class="text-[#0F766E] text-base font-bold mb-1">Tanggapan Akhir</p>
                                <p class="text-[#464646] text-base">{{ $tiket->final_note ?? $tiket->handling_note }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- PENILAIAN ANDA --}}
                    @if($tiket->status === 'completed')
                    <div class="flex-1 bg-white rounded-[20px] border-[0.5px] border-[#E5E5E5] shadow-[0_0_4px_rgba(0,0,0,0.25)] px-6 py-8 md:px-9 md:py-9 text-left">
                        <h2 class="text-[22px] font-extrabold text-[#153655] mb-4">Penilaian Anda</h2>

                        <div x-data="{
                                rating: {{ (int) ($tiket->rating ?? 0) }},
                                hovered: 0,
                                comment: @js($tiket->rating_comment ?? ''),
                             }">
                            <div class="flex flex-col gap-4">
                                <div class="flex items-center gap-[6px]">
                                    @for ($i = 1; $i <= 5; $i++)
                                    <button
                                        type="button"
                                        @click="rating = {{ $i }}"
                                        @mouseenter="hovered = {{ $i }}"
                                        @mouseleave="hovered = 0"
                                        class="leading-none"
                                    >
                                        <svg class="w-[36px] h-[36px]" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 17.27L18.18 21L16.54 13.97L22 9.24L14.81 8.62L12 2L9.19 8.62L2 9.24L7.46 13.97L5.82 21L12 17.27Z"
                                                :fill="(hovered || rating) >= {{ $i }} ? '#FFC400' : '#E5E5E5'"/>
                                        </svg>
                                    </button>
                                    @endfor
                                </div>
                                <textarea
                                    x-model="comment"
                                    rows="3"
                                    placeholder="Tulis komentar Anda (Opsional)"
                                    class="w-full rounded-[20px] border-[0.5px] border-[#A19E9E] bg-[#F9F9F9] px-[22px] py-[14px] text-[18px] text-[#464646] placeholder:text-[#A19E9E] outline-none resize-none"
                                ></textarea>

                                <div class="flex flex-col gap-2">
                                    <div class="flex items-center gap-3">
                                        <form action="{{ Route::has('lacak.nilai') ? route('lacak.nilai', $tiket->ticket_code) : '#' }}" method="POST" x-bind:data-rating="rating">
                                            @csrf
                                            <input type="hidden" name="rating" :value="rating">
                                            <input type="hidden" name="comment" :value="comment">
                                            <button
                                                type="submit"
                                                :disabled="rating === 0"
                                                :class="rating === 0 ? 'opacity-50 cursor-not-allowed' : ''"
                                                class="bg-gradient-to-r from-[#0047AB] to-[#153655] text-white font-semibold text-[18px] rounded-[20px] px-[32px] py-[10px] shadow-[2px_2px_4px_rgba(0,0,0,0.25)]"
                                            >
                                                Perbarui Penilaian
                                            </button>
                                        </form>

                                        {{-- Sesuaikan action route ini dengan endpoint "buka kembali" tiket Anda --}}
                                        <form action="{{ Route::has('lacak.buka-kembali') ? route('lacak.buka-kembali', $tiket->ticket_code) : '#' }}" method="POST">
                                            @csrf
                                            <button
                                                type="submit"
                                                class="flex items-center gap-2 border border-[#A19E9E] text-[#464646] font-semibold text-[18px] rounded-[20px] px-[24px] py-[10px] hover:bg-[#F5F5F5] transition"
                                            >
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M1 4v6h6"/>
                                                    <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/>
                                                </svg>
                                                Buka Kembali
                                            </button>
                                        </form>
                                    </div>
                                    <p x-show="rating === 0" class="text-[#A19E9E] text-sm">Pilih bintang dahulu sebelum menyimpan penilaian.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @endif
                @endisset

                {{-- TIKET TIDAK DITEMUKAN --}}
                @if(!empty($notFound))
                <div class="mt-8 flex items-center justify-center min-h-[100px] px-8 py-6 rounded-[20px] border-[0.5px] border-[#A19E9E] bg-white shadow-[0_0_5px_rgba(0,0,0,0.10)]">
                    <p class="text-[#A19E9E] text-lg font-medium">Tiket tidak ditemukan. Periksa kembali kode Anda.</p>
                </div>
                @endif

            </div>
        </main>
        @include('components.footer')
    </body>
</html>