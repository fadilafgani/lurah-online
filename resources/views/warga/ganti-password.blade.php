<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ubah Kata Sandi – LurahOnline</title>
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
            <div class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 shadow-lg shadow-orange-500/25 mb-4">
                <svg class="w-7 h-7 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight bg-gradient-to-r from-[#0047AB] to-[#153655] bg-clip-text text-transparent">
                Ubah Kata Sandi
            </h1>
            <p class="text-[#464646] text-sm mt-1">
                Amankan akun warga Anda sebelum membuat pengaduan
            </p>
        </div>

        @if(session('info'))
            <div class="mb-5 p-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-900 text-sm flex items-start gap-3">
                <svg class="w-5 h-5 text-[#0047AB] shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        @if(session('warning'))
            <div class="mb-5 p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-sm flex items-start gap-3">
                <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span>{{ session('warning') }}</span>
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
            <div class="mb-5 p-3.5 bg-gray-50 rounded-xl border border-gray-200 text-xs text-slate-700 flex items-center justify-between">
                <div>
                    <span class="text-gray-400 block">Warga:</span>
                    <strong class="text-slate-800 text-sm">{{ $resident->name }}</strong>
                </div>
                <div class="text-right">
                    <span class="text-gray-400 block">NIK:</span>
                    <span class="font-mono text-[#0047AB] font-bold">{{ $resident->nik }}</span>
                </div>
            </div>

            <form action="{{ route('warga.ganti-password.submit') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="current_password" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Kata Sandi Saat Ini <span class="text-rose-500">*</span>
                    </label>
                    <input type="password" name="current_password" id="current_password" required
                        placeholder="Masukkan kata sandi lama / NIK"
                        class="w-full px-4 py-3 bg-gray-50/60 border border-gray-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-[#0047AB] focus:bg-white transition">
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Kata Sandi Baru <span class="text-rose-500">*</span>
                    </label>
                    <input type="password" name="password" id="password" required minlength="6"
                        placeholder="Minimal 6 karakter"
                        class="w-full px-4 py-3 bg-gray-50/60 border border-gray-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-[#0047AB] focus:bg-white transition">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Konfirmasi Kata Sandi Baru <span class="text-rose-500">*</span>
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required minlength="6"
                        placeholder="Ketik ulang kata sandi baru"
                        class="w-full px-4 py-3 bg-gray-50/60 border border-gray-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-[#0047AB] focus:bg-white transition">
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full py-3.5 px-4 bg-gradient-to-r from-[#0047AB] to-[#153655] hover:opacity-95 text-white font-bold rounded-xl shadow-md shadow-blue-600/20 transition-all flex items-center justify-center gap-2">
                        <span>Simpan Kata Sandi & Lanjutkan</span>
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

@include('components.footer')

</body>
</html>
