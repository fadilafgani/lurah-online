<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Publik – LurahOnline</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">

@include('components.navbar')

<main class="w-full pt-[120px] px-24 py-24">

    {{-- ── Page Header ── --}}
    <div class="mb-8 flex items-center gap-4">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-[#0047AB] to-[#001D45] shadow">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
        </div>
        <div>
            <h1 class="text-2xl font-extrabold text-[#0047AB]">Laporan Publik</h1>
            <p class="text-sm text-slate-500">Transparasi data pengaduan warga</p>
        </div>
    </div>

    {{-- ── Stat Cards ── --}}
    <div class="mb-5 grid grid-cols-2 gap-4 md:grid-cols-4">

        {{-- Total --}}
        <div class="relative rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="absolute right-4 top-4 text-slate-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Total Pengaduan</p>
            <p class="mt-2 text-4xl font-extrabold text-slate-800">{{ $totalPengaduan ?? 7 }}</p>
        </div>

        {{-- Diproses --}}
        <div class="relative rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="absolute right-4 top-4 text-amber-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 6v6l4 2"/></svg>
            </div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Sedang diproses</p>
            <p class="mt-2 text-4xl font-extrabold text-slate-800">{{ $sedangDiproses ?? 2 }}</p>
        </div>

        {{-- Selesai --}}
        <div class="relative rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="absolute right-4 top-4 text-emerald-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Selesai</p>
            <p class="mt-2 text-4xl font-extrabold text-slate-800">{{ $selesai ?? 5 }}</p>
            <p class="mt-1 text-xs font-bold text-emerald-500">{{ $pctSelesai ?? 70 }}% tuntas</p>
        </div>

        {{-- Rating --}}
        <div class="relative rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="absolute right-4 top-4 text-amber-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
            </div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Rata-Rata Rating</p>
            <p class="mt-2 text-4xl font-extrabold text-slate-800">{{ number_format($rataRating ?? 4.8, 1) }}</p>
            <p class="mt-1 text-xs font-semibold text-[#0047AB]">{{ $jumlahUlasan ?? 5 }} Ulasan</p>
        </div>

    </div>

    {{-- ── Mid Row ── --}}
    <div class="mb-5 grid grid-cols-1 gap-4 md:grid-cols-2">

        {{-- Trend Chart --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="mb-4 flex items-center gap-2 text-sm font-bold text-[#0047AB]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                Tren 6 Bulan Terakhir
            </p>
            <div class="relative h-24">
                <span class="absolute left-1/2 top-0 -translate-x-1/2 text-xs font-bold text-slate-500">{{ $trendPeak ?? 14 }}</span>
                <svg viewBox="0 0 400 80" preserveAspectRatio="none" fill="none" class="h-full w-full" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="trendGrad" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#0047AB" stop-opacity="0.15"/>
                            <stop offset="100%" stop-color="#0047AB" stop-opacity="0"/>
                        </linearGradient>
                    </defs>
                    <path d="M0 70 C40 70 60 50 100 45 C140 40 160 30 200 15 C240 0 260 20 300 35 C340 50 360 55 400 60 L400 80 L0 80 Z" fill="url(#trendGrad)"/>
                    <path d="M0 70 C40 70 60 50 100 45 C140 40 160 30 200 15 C240 0 260 20 300 35 C340 50 360 55 400 60" stroke="#0047AB" stroke-width="2.5" stroke-linecap="round"/>
                </svg>
            </div>
            <p class="mt-2 text-center text-xs text-slate-400">Apr 26</p>
        </div>

        {{-- Kategori --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="mb-4 flex items-center gap-2 text-sm font-bold text-[#0047AB]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Kategori Pengaduan
            </p>
            @php
                $kategoris = $kategoris ?? [
                    ['nama' => 'Infrastruktur Jalan',      'jumlah' => 4, 'pct' => 80],
                    ['nama' => 'Administrasi Kependudukan','jumlah' => 2, 'pct' => 48],
                    ['nama' => 'Saluran Air / Drainase',   'jumlah' => 1, 'pct' => 20],
                ];
            @endphp
            <div class="space-y-4">
                @foreach($kategoris as $kat)
                <div>
                    <div class="mb-1 flex justify-between text-xs font-semibold text-slate-600">
                        <span>{{ $kat['nama'] }}</span>
                        <span>{{ $kat['jumlah'] }}</span>
                    </div>
                    <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full rounded-full bg-[#0047AB]" style="width: {{ $kat['pct'] }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- ── Daftar Laporan ── --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
            <h2 class="flex items-center gap-2 text-sm font-bold text-slate-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                Daftar Laporan
            </h2>
            <span class="text-xs text-slate-400">{{ count($laporans ?? []) ?: 7 }} Laporan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                        <th class="px-6 py-3">Kode Tiket</th>
                        <th class="px-6 py-3">Judul</th>
                        <th class="px-6 py-3">Kategori</th>
                        <th class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
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
                    @foreach($laporans as $lap)
                    @php
                        $badge = match(strtolower($lap['status'])) {
                            'ditolak'  => 'bg-red-100 text-red-600',
                            'diajukan' => 'bg-blue-100 text-blue-600',
                            'selesai'  => 'bg-emerald-100 text-emerald-600',
                            'diproses' => 'bg-amber-100 text-amber-600',
                            default    => 'bg-slate-100 text-slate-500',
                        };
                        $dot = match(strtolower($lap['status'])) {
                            'ditolak'  => 'bg-red-500',
                            'diajukan' => 'bg-blue-500',
                            'selesai'  => 'bg-emerald-500',
                            'diproses' => 'bg-amber-500',
                            default    => 'bg-slate-400',
                        };
                    @endphp
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-mono text-xs text-slate-500">{{ $lap['kode'] }}</td>
                        <td class="px-6 py-4 font-semibold text-slate-700">{{ $lap['judul'] }}</td>
                        <td class="px-6 py-4 text-slate-500">{{ $lap['kategori'] }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold {{ $badge }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $dot }}"></span>
                                {{ $lap['status'] }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="border-t border-slate-100 px-6 py-3 text-center text-xs text-slate-400">
        Data diperbarui secara realtime berdasarkan pengaduan yang masuk.
    </div>
</main>

@include('components.footer')

</body>
</html>