<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Lupa Kata Sandi - Lurah Online</title>

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

                <div class="w-full rounded-[30px] border border-[#E5E5E5] bg-white shadow-[0_4px_10px_rgba(0,71,171,0.20)] px-6 py-10 sm:px-[72px] sm:pt-[123px] sm:pb-[122px]">

                    <div class="flex flex-col gap-[9px] mb-[38px]">
                        <h1 class="text-[40px] font-extrabold bg-gradient-to-r from-[#0047AB] to-[#001D45] bg-clip-text text-transparent">
                            Lupa Kata Sandi?
                        </h1>
                        <p class="text-[19px] font-medium text-[#464646]">
                            Masukkan email yang terdaftar. Kami akan mengirimkan link untuk mengatur ulang kata sandi Anda.
                        </p>
                    </div>

                 <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-[26px]">
                    @csrf

                    <div class="flex flex-col gap-[10px]">
                        <label for="email" class="text-[19px] font-medium text-[#464646]">
                            Email
                        </label>

                        <div class="flex items-center gap-[17px] h-[65px] px-6 rounded-[20px] border border-[#A19E9E] bg-[#F9F9F9]">
                            <svg class="shrink-0" width="24" height="19" viewBox="0 0 30 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M30 3C30 1.35 28.65 0 27 0H3C1.35 0 0 1.35 0 3V21C0 22.65 1.35 24 3 24H27C28.65 24 30 22.65 30 21V3ZM27 3L15 10.5L3 3H27ZM27 21H3V6L15 13.5L27 6V21Z" fill="#656565"/>
                            </svg>

                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                placeholder="admin@lurah.local"
                                required
                                class="flex-1 bg-transparent border-0 outline-none text-[19px] font-medium text-[#464646] placeholder:text-[#A19E9E]"
                            >
                        </div>

                        @error('email')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        class="w-full py-[18px] rounded-[20px] bg-gradient-to-r from-[#0047AB] to-[#153655] text-white text-[23px] font-semibold shadow-[2px_2px_4px_rgba(0,0,0,0.25)] hover:opacity-90 transition">
                        Kirim
                    </button>

                    <a href="{{ route('admin.login') }}"
                    class="text-[19px] font-medium text-center text-[#0047AB] hover:underline">
                        Kembali ke Login
                    </a>
                </form>
                </div>

                <p class="self-stretch text-center text-[15px] font-medium text-[#464646]">
                    © {{ date('Y') }} Lurah Online — Platform Pengaduan Warga Desa Banjarsari.
                </p>
            </div>
        </main>
    </body>
</html>
