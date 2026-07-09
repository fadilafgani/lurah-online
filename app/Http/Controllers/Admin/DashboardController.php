<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private const STATUS_MAP = [
        'verifikasi' => 'submitted',
        'disposisi' => 'verified',
        'penanganan' => 'process',
        'selesai' => 'completed',
        'ditolak' => 'rejected',
    ];

    private const STATUS_LABELS = [
        'submitted' => 'Diajukan',
        'verified' => 'Disposisi',
        'process' => 'Diproses',
        'completed' => 'Selesai',
        'rejected' => 'Ditolak',
    ];
    
    private const UNIT_OPTIONS = [
    'Unit Infrastruktur',
    'Unit Kebersihan',
    'Unit Keamanan',
    'Unit Administrasi',
    'Unit Umum',
    ];

    public function index(Request $request)
    {
        $status = $request->get('status', 'verifikasi');
        $selectedKode = $request->get('kode');

        $laporan = Complaint::all();

        $statusCards = [
            [
                'key' => 'verifikasi',
                'label' => 'Verifikasi',
                'jumlah' => $laporan->where('status', 'submitted')->count(),
                'active' => $status == 'verifikasi'
            ],
            [
                'key' => 'disposisi',
                'label' => 'Disposisi',
                'jumlah' => $laporan->where('status', 'verified')->count(),
                'active' => $status == 'disposisi'
            ],
            [
                'key' => 'penanganan',
                'label' => 'Penanganan',
                'jumlah' => $laporan->where('status', 'process')->count(),
                'active' => $status == 'penanganan'
            ],
            [
                'key' => 'selesai',
                'label' => 'Selesai',
                'jumlah' => $laporan->where('status', 'completed')->count(),
                'active' => $status == 'selesai'
            ],
            [
                'key' => 'ditolak',
                'label' => 'Ditolak',
                'jumlah' => $laporan->where('status', 'rejected')->count(),
                'active' => $status == 'ditolak'
            ],
        ];

        $laporanList = $laporan
        ->where('status', self::STATUS_MAP[$status])
        ->values()
        ->map(fn (Complaint $item) => [
                'kode' => $item->ticket_code,
                'judul' => $item->title,
                'kategori' => $item->category,
                'status' => self::STATUS_LABELS[$item->status] ?? $item->status,
                'foto' => $item->photo ? asset('storage/' . $item->photo) : null,
                'lokasi' => $item->location,
                'pelapor' => $item->name ?: 'Anonim',
                'diajukan' => optional($item->created_at)->translatedFormat('d M Y, H.i') ?? '-',
                'deskripsi' => $item->description,
                'unit' => $item->unit,
                'catatan' => $item->handling_note,
                'tanggapan' => $item->response_note,
                'alasan' => $item->rejection_reason,
            ]);
            
        $selectedComplaint = $laporanList ->firstWhere('kode', $selectedKode) ?? $laporanList->first();

        $unitOptions = self::UNIT_OPTIONS;

        return view('admin.dashboard', compact(
            'statusCards',
            'laporanList',
            'status',
            'selectedKode',
            'selectedComplaint',
            'unitOptions'
        ));
    }
}
