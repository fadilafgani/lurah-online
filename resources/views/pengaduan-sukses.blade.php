<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Lurah Online</title>

        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            .kode-tiket { font-family: 'Cascadia Code', 'Plus Jakarta Sans', monospace; }
        </style>
    </head>
    <body class="bg-white text-slate-900 antialiased">
        @include('components.navbar')
        <div class="relative w-full overflow-hidden">

            <div aria-hidden="true" class="pointer-events-none absolute inset-x-0 top-[271px] -z-10 flex justify-between blur-[350px]">
                <div class="-ml-24 h-[280px] w-[280px] shrink-0 rounded-full bg-[#0047AB]"></div>
                <div class="-mr-24 h-[220px] w-[460px] shrink-0 rounded-full bg-[#00B4D8] opacity-60"></div>
            </div>

            <main class="max-w-3xl mx-auto w-full px-4 pt-[126px] pb-20 flex flex-col items-center gap-12">

                <div class="w-full max-w-xl flex flex-col items-center gap-6 text-center">
                    <div class="flex items-center justify-center rounded-full bg-gradient-to-br from-[#0040A1] to-[#2472E3] shadow-[0_4px_10px_0_rgba(0,0,0,0.25),0_2px_4px_0_rgba(0,0,0,0.05)_inset]" style="width:108px;height:108px;">
                        <svg width="54" height="54" viewBox="0 0 54 54" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18.7394 49L2 32.6158L9.62844 25.1493L18.7394 34.0933L45.3716 8L53 15.4665L18.7394 49Z" fill="white"/>
                        </svg>
                    </div>

                    <h1 class="text-4xl font-extrabold tracking-tight bg-gradient-to-r from-[#0047AB] to-[#153655] bg-clip-text text-transparent">
                        Pengaduan Berhasil Terkirim!
                    </h1>
                    <p class="text-[22px] font-medium text-[#464646]">
                        Terima kasih telah berkontribusi untuk Desa Banjarsari yang lebih baik. Aspirasi Anda telah kami terima dan siap untuk ditindaklanjuti.
                    </p>
                </div>

                <div class="w-full rounded-[30px] border border-[#E5E5E5] bg-white shadow-[0_4px_10px_0_rgba(0,0,0,0.25)] px-6 py-10 md:px-12 md:py-10">
                    <div class="flex flex-col items-center gap-4">
                        <div class="flex flex-col items-center gap-1 w-full">
                            <span class="text-[22px] font-medium text-[#0047AB]">ID Laporan Anda</span>
                            <div class="flex items-center gap-4">
                                <span id="kode-tiket" class="kode-tiket text-3xl md:text-5xl font-bold text-[#153655] tracking-wide">{{ $kode ?? 'LO-12345-AB789' }}</span>
                                <div class="relative shrink-0">
                                    <button type="button" id="copy-kode" title="Salin kode" class="shrink-0 cursor-pointer">
                                        <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M8.16675 11.278C8.16675 10.4528 8.49457 9.66136 9.07809 9.07784C9.6616 8.49432 10.453 8.1665 11.2782 8.1665H21.3886C21.7972 8.1665 22.2018 8.24699 22.5793 8.40335C22.9568 8.55972 23.2998 8.78891 23.5887 9.07784C23.8777 9.36677 24.1069 9.70978 24.2632 10.0873C24.4196 10.4648 24.5001 10.8694 24.5001 11.278V21.3883C24.5001 21.7969 24.4196 22.2016 24.2632 22.5791C24.1069 22.9566 23.8777 23.2996 23.5887 23.5885C23.2998 23.8774 22.9568 24.1066 22.5793 24.263C22.2018 24.4194 21.7972 24.4998 21.3886 24.4998H11.2782C10.8696 24.4998 10.465 24.4194 10.0875 24.263C9.71002 24.1066 9.36701 23.8774 9.07809 23.5885C8.78916 23.2996 8.55996 22.9566 8.4036 22.5791C8.24723 22.2016 8.16675 21.7969 8.16675 21.3883V11.278Z" stroke="#0047AB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M4.68067 19.5265C4.3225 19.323 4.02459 19.0283 3.81722 18.6724C3.60984 18.3164 3.5004 17.9119 3.5 17.5V5.83333C3.5 4.55 4.55 3.5 5.83333 3.5H17.5C18.375 3.5 18.851 3.94917 19.25 4.66667" stroke="#0047AB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                    <span class="copy-tooltip pointer-events-none absolute left-1/2 -translate-x-1/2 -top-9 whitespace-nowrap rounded-md bg-[#153655] px-2.5 py-1 text-xs font-semibold text-white opacity-0 transition-opacity duration-200">Tersalin!</span>
                                </div>
                            </div>
                        </div>

                        <div class="w-full flex items-start gap-4 rounded-[20px] bg-[#F0F4FA] px-6 py-7">
                            <div class="relative shrink-0">
                                <button type="button" id="copy-kode-info" title="Salin kode" class="shrink-0 cursor-pointer">
                                    <svg class="shrink-0" width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M15.8913 20.8912C16.1304 20.6512 16.25 20.3542 16.25 20V15C16.25 14.6458 16.13 14.3492 15.89 14.11C15.65 13.8708 15.3533 13.7508 15 13.75C14.6467 13.7492 14.35 13.8692 14.11 14.11C13.87 14.3508 13.75 14.6475 13.75 15V20C13.75 20.3542 13.87 20.6512 14.11 20.8912C14.35 21.1312 14.6467 21.2508 15 21.25C15.3533 21.2492 15.6504 21.1304 15.8913 20.8912ZM15.8913 10.89C16.1304 10.6508 16.25 10.3542 16.25 10C16.25 9.64583 16.13 9.34917 15.89 9.11C15.65 8.87083 15.3533 8.75083 15 8.75C14.6467 8.74917 14.35 8.86917 14.11 9.11C13.87 9.35083 13.75 9.6475 13.75 10C13.75 10.3525 13.87 10.6496 14.11 10.8913C14.35 11.1329 14.6467 11.2525 15 11.25C15.3533 11.2475 15.6504 11.1275 15.8913 10.89ZM15 27.5C13.2708 27.5 11.6458 27.1717 10.125 26.515C8.60417 25.8583 7.28125 24.9679 6.15625 23.8438C5.03125 22.7196 4.14084 21.3967 3.485 19.875C2.82917 18.3533 2.50083 16.7283 2.5 15C2.49917 13.2717 2.8275 11.6467 3.485 10.125C4.1425 8.60333 5.03292 7.28042 6.15625 6.15625C7.27958 5.03208 8.6025 4.14167 10.125 3.485C11.6475 2.82833 13.2725 2.5 15 2.5C16.7275 2.5 18.3525 2.82833 19.875 3.485C21.3975 4.14167 22.7204 5.03208 23.8438 6.15625C24.9671 7.28042 25.8579 8.60333 26.5163 10.125C27.1746 11.6467 27.5025 13.2717 27.5 15C27.4975 16.7283 27.1692 18.3533 26.515 19.875C25.8608 21.3967 24.9704 22.7196 23.8438 23.8438C22.7171 24.9679 21.3942 25.8587 19.875 26.5162C18.3558 27.1737 16.7308 27.5017 15 27.5ZM15 25C17.7917 25 20.1562 24.0312 22.0938 22.0938C24.0312 20.1562 25 17.7917 25 15C25 12.2083 24.0312 9.84375 22.0938 7.90625C20.1562 5.96875 17.7917 5 15 5C12.2083 5 9.84375 5.96875 7.90625 7.90625C5.96875 9.84375 5 12.2083 5 15C5 17.7917 5.96875 20.1562 7.90625 22.0938C9.84375 24.0312 12.2083 25 15 25Z" fill="#0047AB"/>
                                    </svg>
                                </button>
                                <span class="copy-tooltip pointer-events-none absolute left-1/2 -translate-x-1/2 -top-9 whitespace-nowrap rounded-md bg-[#153655] px-2.5 py-1 text-xs font-semibold text-white opacity-0 transition-opacity duration-200">Tersalin!</span>
                            </div>
                            <p class="text-[16px] font-medium text-[#464646]">
                                Simpan atau salin kode ini untuk melacak perkembangan laporan Anda secara real-time melalui halaman <a href="{{ url('/lacak') }}" class="font-bold text-[#0047AB]">Lacak Pengaduan</a>.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="w-full flex flex-col sm:flex-row items-center justify-center gap-6">
                    <a href="{{ url('/lacak') }}{{ isset($kode) ? '?kode=' . $kode : '' }}"
                        class="w-full sm:w-auto flex items-center justify-center gap-4 px-16 py-[18px] rounded-[20px] bg-gradient-to-r from-[#0047AB] to-[#153655] shadow-[2px_2px_4px_0_rgba(0,0,0,0.25)]">
                        <span class="text-[23px] font-semibold text-white">Lacak Sekarang</span>
                        <svg width="25" height="14" viewBox="0 0 50 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M39.5312 15.5554H19.8438C19.2312 15.5554 18.75 14.871 18.75 13.9999C18.75 13.1288 19.2312 12.4443 19.8438 12.4443H39.5312C40.1437 12.4443 40.625 13.1288 40.625 13.9999C40.625 14.871 40.1437 15.5554 39.5312 15.5554Z" fill="white"/>
                            <path d="M31.25 26.4443C31.0451 26.4468 30.8419 26.4065 30.6535 26.3262C30.4652 26.2458 30.2958 26.1271 30.1563 25.9776C29.5313 25.3554 29.5313 24.3909 30.1563 23.7687L40 13.9687L30.1563 4.16872C29.5313 3.5465 29.5313 2.58205 30.1563 1.95983C30.7812 1.33761 31.75 1.33761 32.375 1.95983L43.3125 12.8487C43.9375 13.4709 43.9375 14.4354 43.3125 15.0576L32.375 25.9465C32.0625 26.2576 31.6563 26.4132 31.2813 26.4132L31.25 26.4443Z" fill="white"/>
                        </svg>
                    </a>
                    <a href="{{ url('/pengaduan') }}"
                        class="w-full sm:w-auto flex items-center justify-center px-16 py-[18px] rounded-[20px] border-[0.5px] border-[#A19E9E] bg-white shadow-[2px_2px_4px_0_rgba(0,0,0,0.25)]">
                        <span class="text-[23px] font-semibold text-[#153655]">Buat Pengaduan Lain</span>
                    </a>
                </div>

            </main>
        </div>
        @include('components.footer')

        <script>
            function copyKodeTiket(button) {
                const kode = document.getElementById('kode-tiket').textContent.trim();
                navigator.clipboard.writeText(kode);

                const tooltip = button.parentElement.querySelector('.copy-tooltip');
                tooltip.classList.remove('opacity-0');
                tooltip.classList.add('opacity-100');
                clearTimeout(tooltip.hideTimeout);
                tooltip.hideTimeout = setTimeout(function () {
                    tooltip.classList.remove('opacity-100');
                    tooltip.classList.add('opacity-0');
                }, 1500);
            }

            document.getElementById('copy-kode').addEventListener('click', function () {
                copyKodeTiket(this);
            });
            document.getElementById('copy-kode-info').addEventListener('click', function () {
                copyKodeTiket(this);
            });
        </script>
    </body>
</html>
