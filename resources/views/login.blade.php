<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Masuk Admin/Unit - Lurah Online</title>

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

                <div class="w-full rounded-[30px] border border-[#E5E5E5] bg-white shadow-[0_4px_10px_rgba(0,71,171,0.20)] px-6 py-10 sm:px-[72px] sm:pt-20 sm:pb-[68px]">

                    <div class="flex flex-col gap-[9px] mb-[38px]">
                        <h1 class="text-[40px] font-extrabold bg-gradient-to-r from-[#0047AB] to-[#001D45] bg-clip-text text-transparent">
                            Masuk Admin/Unit
                        </h1>
                        <p class="text-[19px] font-medium text-[#464646]">
                            Khusus untuk staf kelurahan yang berwenang.
                        </p>
                    </div>

                    <form id="login-form" class="flex flex-col gap-[26px]">
                        <div class="flex flex-col gap-[10px]">
                            <label for="email" class="text-[19px] font-medium text-[#464646]">Email</label>
                            <div class="flex items-center gap-[17px] h-[65px] px-6 rounded-[20px] border border-[#A19E9E] bg-[#F9F9F9]">
                                <svg class="shrink-0" width="24" height="19" viewBox="0 0 30 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M30 3C30 1.35 28.65 0 27 0H3C1.35 0 0 1.35 0 3V21C0 22.65 1.35 24 3 24H27C28.65 24 30 22.65 30 21V3ZM27 3L15 10.5L3 3H27ZM27 21H3V6L15 13.5L27 6V21Z" fill="#656565"/>
                                </svg>
                                <input id="email" name="email" type="email" placeholder="admin@lurah.local" required
                                    class="flex-1 bg-transparent border-0 outline-none text-[19px] font-medium text-[#464646] placeholder:text-[#A19E9E]">
                            </div>
                        </div>

                        <div class="flex flex-col gap-[10px]">
                            <label for="password" class="text-[19px] font-medium text-[#464646]">Password</label>
                            <div class="flex items-center gap-[17px] h-[65px] px-6 rounded-[20px] border border-[#A19E9E] bg-[#F9F9F9]">
                                <svg class="shrink-0" width="24" height="24" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M19 3.16663C21.4579 3.16654 23.8202 4.11911 25.5906 5.8242C27.3609 7.52929 28.4015 9.85417 28.4937 12.3104L28.5 12.6666H30.0833C30.8822 12.6664 31.6517 12.9681 32.2375 13.5113C32.8233 14.0546 33.1822 14.7991 33.2421 15.5958L33.25 15.8333V31.6666C33.2503 32.4655 32.9485 33.235 32.4053 33.8208C31.8621 34.4066 31.1175 34.7655 30.3208 34.8254L30.0833 34.8333H7.91667C7.11775 34.8335 6.34827 34.5318 5.76247 33.9886C5.17666 33.4454 4.81784 32.7008 4.75792 31.9041L4.75 31.6666V15.8333C4.74975 15.0344 5.05148 14.2649 5.5947 13.6791C6.13793 13.0933 6.8825 12.7345 7.67917 12.6745L7.91667 12.6666H9.5C9.5 10.1471 10.5009 7.73071 12.2825 5.94911C14.0641 4.16752 16.4804 3.16663 19 3.16663ZM30.0833 15.8333H7.91667V31.6666H30.0833V15.8333ZM19 19C19.6754 19.0002 20.3331 19.2163 20.8769 19.6169C21.4208 20.0175 21.8223 20.5814 22.0229 21.2264C22.2234 21.8714 22.2125 22.5636 21.9917 23.2019C21.7708 23.8402 21.3517 24.3912 20.7955 24.7744L20.5833 24.909V26.9166C20.5829 27.3202 20.4284 27.7083 20.1513 28.0018C19.8743 28.2952 19.4957 28.4718 19.0928 28.4955C18.6899 28.5191 18.2932 28.3881 17.9838 28.129C17.6743 27.87 17.4754 27.5026 17.4278 27.1019L17.4167 26.9166V24.909C16.813 24.5604 16.3412 24.0224 16.0745 23.3784C15.8078 22.7344 15.761 22.0203 15.9414 21.347C16.1218 20.6737 16.5193 20.0787 17.0723 19.6544C17.6254 19.23 18.3029 19 19 19ZM19 6.33329C17.3203 6.33329 15.7094 7.00055 14.5217 8.18828C13.3339 9.37601 12.6667 10.9869 12.6667 12.6666H25.3333C25.3333 10.9869 24.6661 9.37601 23.4783 8.18828C22.2906 7.00055 20.6797 6.33329 19 6.33329Z" fill="#656565"/>
                                </svg>
                                <input id="password" name="password" type="password" placeholder="••••••••••" required
                                    class="flex-1 bg-transparent border-0 outline-none text-[19px] font-medium text-[#464646] placeholder:text-[#A19E9E]">
                                <button type="button" id="toggle-password" class="shrink-0">
                                    <svg width="24" height="24" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M13.75 16.5C13.75 17.2293 14.0397 17.9288 14.5555 18.4445C15.0712 18.9603 15.7707 19.25 16.5 19.25C17.2293 19.25 17.9288 18.9603 18.4445 18.4445C18.9603 17.9288 19.25 17.2293 19.25 16.5C19.25 15.7707 18.9603 15.0712 18.4445 14.5555C17.9288 14.0397 17.2293 13.75 16.5 13.75C15.7707 13.75 15.0712 14.0397 14.5555 14.5555C14.0397 15.0712 13.75 15.7707 13.75 16.5Z" stroke="#656565" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M28.875 16.5C25.575 22 21.45 24.75 16.5 24.75C11.55 24.75 7.425 22 4.125 16.5C7.425 11 11.55 8.25 16.5 8.25C21.45 8.25 25.575 11 28.875 16.5Z" stroke="#656565" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <label class="flex items-center gap-[10px] cursor-pointer">
                                <input type="checkbox" name="remember" class="h-5 w-5 rounded-[5px] border border-[#A19E9E] text-[#0047AB] focus:ring-0">
                                <span class="text-[19px] font-medium text-[#464646]">Ingat Saya</span>
                            </label>
                            <a href="{{ route('admin.forgot-password') }}" class="text-[19px] font-medium text-[#0047AB] hover:underline">Lupa Kata Sandi?</a>
                        </div>

                        <button type="submit"
                            class="w-full py-[18px] rounded-[20px] bg-gradient-to-r from-[#0047AB] to-[#153655] text-white text-[23px] font-semibold shadow-[2px_2px_4px_rgba(0,0,0,0.25)] hover:opacity-90 transition">
                            Masuk
                        </button>
                    </form>
                </div>

                <p class="self-stretch text-center text-[15px] font-medium text-[#464646]">
                    © {{ date('Y') }} Lurah Online — Platform Pengaduan Warga Desa Banjarsari.
                </p>
            </div>
        </main>

        <script>
            document.getElementById('toggle-password').addEventListener('click', function () {
                const input = document.getElementById('password');
                input.type = input.type === 'password' ? 'text' : 'password';
            });

            document.getElementById('login-form').addEventListener('submit', async function (e) {
                e.preventDefault();

                const response = await fetch('/api/admin/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        email: document.getElementById('email').value,
                        password: document.getElementById('password').value,
                    }),
                });

                const data = await response.json();

                if (!response.ok) {
                    alert(data.message ?? 'Email atau password salah');
                    return;
                }

                localStorage.setItem('admin_token', data.token);
                window.location.href = '{{ route('admin.dashboard') }}';
            });
        </script>
    </body>
</html>
