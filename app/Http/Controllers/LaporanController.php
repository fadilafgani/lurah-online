<?php

namespace App\Http\Controllers;

use App\Models\Complaint;

class LaporanController extends Controller
{
    private const STATUS_LABELS = [
        'submitted' => 'Diajukan',
        'verified' => 'Disposisi',
        'process' => 'Diproses',
        'completed' => 'Selesai',
        'rejected' => 'Ditolak',
    ];

    public function index()
    {
        $laporans = Complaint::latest()->get()->map(fn (Complaint $item) => [
            'kode' => $item->ticket_code,
            'judul' => $item->title,
            'kategori' => $item->category,
            'status' => self::STATUS_LABELS[$item->status] ?? $item->status,
            'foto' => $item->photo ? asset('storage/' . $item->photo) : null,
        ]);

        return view('laporan', compact('laporans'));
    }
}
