<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk Warga – LurahOnline</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-white text-slate-900 antialiased">

@include('components.navbar')

<main class="relative overflow-hidden flex-1 pt-[126px] pb-16 flex items-center justify-center px-4">

    {{-- Ambient background blobs --}}
    <div aria-hidden="true" class="pointer-events-none absolute inset-x-0 top-[200px] -z-10 flex justify-between blur-[350px]">
        <div class="-ml-24 h-[280px] w-[280px] shrink-0 rounded-full bg-[#0047AB]"></div>
        <div class="-mr-24 h-[220px] w-[460px] shrink-0 rounded-full bg-[#00B4D8] opacity-60"></div>
    </div>

    <div class="w-full max-w-md mx-auto">

        <div class="text-center mb-8">
            <div class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-[#0047AB] to-[#153655] shadow-lg shadow-blue-500/25 mb-4">
                <svg class="w-7 h-7 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="16" rx="3"/>
                    <circle cx="9" cy="10" r="2"/>
                    <line x1="15" y1="8" x2="17" y2="8"/>
                    <line x1="15" y1="12" x2="17" y2="12"/>
                    <line x1="7" y1="16" x2="17" y2="16"/>
                </svg>
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight bg-gradient-to-r from-[#0047AB] to-[#153655] bg-clip-text text-transparent">
                Masuk Warga
            </h1>
            <p class="text-[#464646] text-sm mt-1">
                Gunakan NIK Anda untuk membuat pengaduan resmi
            </p>
        </div>

        {{-- Alerts --}}
        @if(session('warning'))
            <div class="mb-5 p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-sm flex items-start gap-3">
                <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        @if(session('info'))
            <div class="mb-5 p-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-900 text-sm flex items-start gap-3">
                <svg class="w-5 h-5 text-[#0047AB] shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-5 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 text-sm">
                <div class="flex items-center gap-2 font-bold mb-1">
                    <svg class="w-4 h-4 text-rose-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    <span>Terjadi Kesalahan:</span>
                </div>
                <ul class="list-disc list-inside space-y-1 text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-[0_10px_40px_rgba(0,71,171,0.12)] border border-gray-100 p-8">
            <form action="{{ route('warga.login.submit') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="nik" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Nomor Induk Kependudukan (NIK) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        </div>
                        <input type="text" name="nik" id="nik" required autofocus
                            value="{{ old('nik') }}"
                            placeholder="Contoh: 320501xxxxxxxxxx"
                            class="w-full pl-11 pr-4 py-3 bg-gray-50/60 border border-gray-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-[#0047AB] focus:bg-white transition font-mono tracking-wide">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Kata Sandi <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </div>
                        <input type="password" name="password" id="password" required
                            placeholder="Kata sandi awal adalah NIK Anda"
                            class="w-full pl-11 pr-11 py-3 bg-gray-50/60 border border-gray-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-[#0047AB] focus:bg-white transition">
                        <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                            <svg id="eye-icon" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-600">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-[#0047AB] focus:ring-[#0047AB]">
                        <span>Ingat saya</span>
                    </label>
                    <span class="text-gray-400">Desa Banjarsari</span>
                </div>

                <button type="submit"
                    class="w-full py-3.5 px-4 bg-gradient-to-r from-[#0047AB] to-[#153655] hover:opacity-95 text-white font-bold rounded-xl shadow-md shadow-blue-600/20 transition-all flex items-center justify-center gap-2">
                    <span>Masuk ke Layanan</span>
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-gray-100 bg-blue-50/40 rounded-xl p-3.5">
                <div class="flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-[#0047AB] shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    <p class="text-[12px] text-slate-600 leading-relaxed">
                        <strong class="text-slate-800">Petunjuk Pertama Kali:</strong> Untuk warga yang belum pernah login, kata sandi Anda sama dengan NIK. Anda akan diminta untuk mengganti kata sandi setelah berhasil login.
                    </p>
                </div>
            </div>
        </div>

        <p class="text-center text-xs text-gray-500 mt-6">
            Belum terdaftar sebagai warga Desa Banjarsari? Hubungi kantor desa/kelurahan setempat.
        </p>
    </div>
</main>

@include('components.footer')

<script>
document.addEventListener("DOMContentLoaded", function () {
    const toggleBtn = document.getElementById('toggle-password');
    const pwdInput = document.getElementById('password');

    toggleBtn?.addEventListener('click', function () {
        const isPassword = pwdInput.type === 'password';
        pwdInput.type = isPassword ? 'text' : 'password';
    });
});
</script>

</body>
</html>
