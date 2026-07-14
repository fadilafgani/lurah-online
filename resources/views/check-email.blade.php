<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Periksa Email Anda - Lurah Online</title>

        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
        </style>
    </head>
    <body class="bg-white text-slate-900 antialiased">
        @include('components.navbar')

        <main class="relative overflow-hidden min-h-screen pt-[126px] flex items-center justify-center px-4 pb-16"
            style="background: linear-gradient(135deg,#e8eef7 0%,#dce6f5 50%,#e4eaf6 100%);">

            <div class="pointer-events-none absolute -left-14 top-6 h-96 w-72 rounded-full bg-[#0047AB] blur-[120px]"></div>
            <div class="pointer-events-none absolute right-0 bottom-0 h-52 w-80 rounded-full bg-[#00B4D8]/60 blur-[120px]"></div>

            <div class="relative w-full max-w-[561px] flex flex-col items-center gap-[25px]">

                <div class="w-full rounded-[30px] border border-[#E5E5E5] bg-white shadow-[0_4px_10px_rgba(0,71,171,0.20)] px-6 py-10 sm:px-[72px] sm:py-[88px] flex flex-col items-center">

                    <div class="flex flex-col items-center gap-[23px] w-full sm:max-w-[417px]">
                        <div class="flex items-center justify-center w-[157px] h-[157px] rounded-full bg-white shadow-[0_4px_10px_rgba(0,0,0,0.25)]">
                            <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M47.5 60H10L9.9925 22.265L38.5775 42.055C38.9955 42.3442 39.4917 42.4992 40 42.4992C40.5083 42.4992 41.0045 42.3442 41.4225 42.055L70 22.275V45H75V20C74.998 18.6745 74.4706 17.4039 73.5333 16.4667C72.5961 15.5294 71.3255 15.002 70 15H10C8.67392 15 7.40215 15.5268 6.46447 16.4645C5.52678 17.4021 5 18.6739 5 20V60C5.00198 61.3255 5.5294 62.5961 6.46666 63.5333C7.40391 64.4706 8.67453 64.998 10 65H47.5V60ZM64.4975 20L40 36.96L15.5025 20H64.4975Z" fill="#D89C00"/>
                                <path d="M65 70C70.5228 70 75 65.5228 75 60C75 54.4772 70.5228 50 65 50C59.4772 50 55 54.4772 55 60C55 65.5228 59.4772 70 65 70Z" fill="#D89C00"/>
                            </svg>
                        </div>

                        <div class="flex flex-col items-start gap-[38px] w-full">
                            <div class="flex flex-col items-start gap-[9px] w-full">
                                <h1 class="self-stretch text-center text-[40px] font-extrabold bg-gradient-to-r from-[#0047AB] to-[#001D45] bg-clip-text text-transparent">
                                    Periksa Email Anda.
                                </h1>
                                <p class="self-stretch text-center text-[19px] font-medium text-[#464646]">
                                    Kami telah mengirimkan link untuk mengatur ulang kata sandi ke email Anda. Jika tidak menemukan email, cek folder spam atau promosi.
                                </p>
                            </div>
                        </div>

                        <a href="{{ route('admin.login') }}"
                            class="w-full flex items-center justify-center py-[18px] rounded-[20px] bg-gradient-to-r from-[#0047AB] to-[#153655] text-white text-[23px] font-semibold shadow-[2px_2px_4px_rgba(0,0,0,0.25)] hover:opacity-90 transition">
                            Kembali ke Login
                        </a>

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <input type="hidden" name="email" value="{{ session('email') }}">

                            <button
                                type="submit"
                                class="self-stretch text-[19px] font-medium text-center text-[#0047AB] hover:underline">
                                Kirim Ulang Email
                            </button>
                        </form>
                    </div>
                </div>

                <p class="self-stretch text-center text-[15px] font-medium text-[#464646]">
                    © {{ date('Y') }} Lurah Online — Platform Pengaduan Warga Desa Banjarsari.
                </p>
            </div>
        </main>
    </body>
</html>
