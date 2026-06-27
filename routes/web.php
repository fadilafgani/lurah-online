<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/pengaduan', function () {
    return view('pengaduan');
})->name('pengaduan.buat');

Route::post('/pengaduan/simpan', function (Request $request) {
    $request->validate([
        'judul' => 'required|string|max:255',
        'kategori' => 'required',
        'deskripsi' => 'required',
        'lokasi' => 'required',
        'foto' => 'required|image|max:5120',
        'nama' => 'nullable|string|max:255',
    ]);

    return back()->with('success', 'Pengaduan berhasil dikirim!');
})->name('pengaduan.simpan');

Route::get('/lacak', fn() => view('lacak'))->name('lacak');

Route::get('/lacak/cari', function (Request $request) {
    $tiket = \App\Models\Pengaduan::where('kode_tiket', $request->kode)->first();

    if (!$tiket) {
        return redirect()->route('lacak.cari', ['kode' => $request->kode])
                         ->with('error', 'Tiket "' . $request->kode . '" tidak ditemukan.');
    }

    return view('lacak', compact('tiket'));
})->name('lacak.cari');

use App\Http\Controllers\LaporanController;

Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');