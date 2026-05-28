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