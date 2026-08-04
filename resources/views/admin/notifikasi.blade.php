<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Semua Notifikasi – LurahOnline</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="manifest" href="/manifest.json">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-white text-slate-900 antialiased">

@include('components.navbar-admin')

<main class="relative overflow-hidden pt-[126px] pb-16">

    {{-- Ambient background blobs --}}
    <div aria-hidden="true" class="pointer-events-none absolute inset-x-0 top-[271px] -z-10 flex justify-between blur-[350px]">
        <div class="-ml-24 h-[280px] w-[280px] shrink-0 rounded-full bg-[#0047AB]"></div>
        <div class="-mr-24 h-[220px] w-[460px] shrink-0 rounded-full bg-[#00B4D8] opacity-60"></div>
    </div>

    <div class="mx-auto flex w-full max-w-[1283px] flex-col items-start gap-[30px] px-6 sm:px-8 lg:px-4">

        {{-- Page Header --}}
        <div class="flex items-end gap-3.5">
            <div class="flex h-[53px] w-[53px] shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[#0047AB] to-[#153655]">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 22C13.1 22 14 21.1 14 20H10C10 21.1 10.9 22 12 22ZM18 16V11C18 7.93 16.37 5.36 13.5 4.68V4C13.5 3.17 12.83 2.5 12 2.5C11.17 2.5 10.5 3.17 10.5 4V4.68C7.64 5.36 6 7.92 6 11V16L4 18V19H20V18L18 16Z" fill="white"/>
                </svg>
            </div>
            <div class="flex min-w-0 flex-1 flex-col items-start gap-0.5">
                <h1 class="bg-gradient-to-r from-[#0047AB] to-[#153655] bg-clip-text text-[22px] sm:text-[30px] font-extrabold leading-tight text-transparent">
                    Semua Notifikasi
                </h1>
                <p class="text-[15px] sm:text-[18px] font-medium text-[#464646]">Daftar riwayat dan status seluruh pengaduan warga.</p>
            </div>
        </div>

        @php
            $filters = [
                'all' => 'Semua',
                'submitted' => 'Diajukan',
                'verified' => 'Disposisi',
                'process' => 'Diproses',
                'completed' => 'Selesai',
                'rejected' => 'Ditolak',
            ];
            $currentFilter = request('filter', 'all');
        @endphp

        {{-- Filters --}}
        <div class="flex flex-wrap items-center gap-2 sm:gap-3">
            @foreach ($filters as $key => $label)
                <a href="{{ route('admin.notifikasi', ['filter' => $key]) }}"
                   class="rounded-full px-5 py-2 text-sm font-semibold transition-all {{ $currentFilter === $key ? 'bg-[#0047AB] text-white shadow-sm' : 'border border-slate-300 bg-white text-[#464646] hover:bg-slate-50' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        {{-- Notifications List Container --}}
        <div class="w-full rounded-[20px] border-[0.5px] border-[#A19E9E] bg-white shadow-[0_4px_10px_0_rgba(0,0,0,0.20)] overflow-hidden">
            <div class="flex flex-col divide-y divide-slate-100">
                @forelse ($notifikasiList as $item)
                    @php
                        $badge = match (strtolower($item->status)) {
                            'rejected', 'ditolak' => 'border-[#D83D3D] bg-[#FFD9D9] text-[#D83D3D]',
                            'submitted', 'diajukan' => 'border-[#2E77BB] bg-[#CCF4FF] text-[#2E77BB]',
                            'completed', 'selesai' => 'border-[#098A00] bg-[#C9ECC1] text-[#098A00]',
                            'process', 'verified', 'diproses', 'disposisi' => 'border-[#D87A00] bg-[#FFF2C7] text-[#D87A00]',
                            default => 'border-[#A19E9E] bg-[#E5E5E5] text-[#464646]',
                        };

                        $statusLabel = match (strtolower($item->status)) {
                            'submitted' => 'Diajukan',
                            'verified' => 'Disposisi',
                            'process' => 'Diproses',
                            'completed' => 'Selesai',
                            'rejected' => 'Ditolak',
                            default => ucfirst($item->status),
                        };

                        $dashboardStatusKey = match (strtolower($item->status)) {
                            'submitted' => 'verifikasi',
                            'verified' => 'disposisi',
                            'process' => 'penanganan',
                            'completed' => 'selesai',
                            'rejected' => 'ditolak',
                            default => 'verifikasi',
                        };
                    @endphp
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-6 py-5 hover:bg-slate-50 transition-colors">
                        <div class="flex flex-col items-start gap-1">
                            <div class="flex items-center gap-3">
                                <span class="font-mono text-sm font-semibold text-slate-500">{{ $item->ticket_code }}</span>
                                <span class="inline-flex items-center rounded-full border-[0.5px] px-3 py-0.5 text-xs font-semibold {{ $badge }}">
                                    {{ $statusLabel }}
                                </span>
                                <span class="text-xs text-slate-400 font-medium">
                                    {{ optional($item->created_at)->diffForHumans() ?? '-' }}
                                </span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 mt-1">{{ $item->title }}</h3>
                            <p class="text-sm font-medium text-slate-600 line-clamp-1">{{ $item->description }}</p>
                            <div class="flex items-center gap-4 mt-2 text-xs font-medium text-slate-500">
                                <span>Kategori: <strong>{{ $item->category }}</strong></span>
                                <span>Pelapor: <strong>{{ $item->name ?: 'Anonim' }}</strong></span>
                                <span>Lokasi: <strong>{{ $item->location }}</strong></span>
                            </div>
                        </div>

                        <div class="shrink-0 pt-2 sm:pt-0">
                            <a href="{{ route('admin.dashboard', ['status' => $dashboardStatusKey, 'kode' => $item->ticket_code]) }}"
                               class="inline-flex items-center justify-center rounded-xl bg-[#153655] px-5 py-2 text-sm font-bold text-white hover:bg-[#0047AB] transition-colors shadow-sm">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center text-slate-400 font-medium">
                        Tidak ada notifikasi ditemukan.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Pagination --}}
        @if ($notifikasiList->hasPages())
            <div class="w-full">
                {{ $notifikasiList->links() }}
            </div>
        @endif

    </div>
</main>

@include('components.footer')

</body>
</html>
