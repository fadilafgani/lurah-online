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

    private const UNIT_TABS = ['penanganan', 'selesai'];

    public function index(Request $request)
{
    $user = $request->user();
    $isUnit = $user->role === 'unit';
    $defaultTab = $isUnit ? 'penanganan' : 'verifikasi';

    $status = $request->get('status', $defaultTab);
    $selectedKode = $request->get('kode');

    if (! isset(self::STATUS_MAP[$status])) {
        $status = $defaultTab;
    }

    // Unit hanya boleh membuka tab Penanganan & Selesai
    if ($isUnit && ! in_array($status, self::UNIT_TABS, true)) {
        return redirect()->route('admin.dashboard', ['status' => 'penanganan']);
    }

    $laporan = Complaint::with('resident')
        ->when($isUnit, fn ($q) => $q
            ->where('unit', $user->unit)      // laporan unit lain tidak ikut terambil
            ->whereIn('status', ['process', 'completed']))
        ->latest()
        ->get();

    $labels = [
        'verifikasi' => 'Verifikasi',
        'disposisi' => 'Disposisi',
        'penanganan' => 'Penanganan',
        'selesai' => 'Selesai',
        'ditolak' => 'Ditolak',
    ];

    $statusCards = collect($labels)
        ->filter(fn ($label, $key) => ! $isUnit || in_array($key, self::UNIT_TABS, true))
        ->map(fn ($label, $key) => [
            'key' => $key,
            'label' => $label,
            'jumlah' => $laporan->where('status', self::STATUS_MAP[$key])->count(),
            'active' => $status === $key,
        ])
        ->values()
        ->all();

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
            'pelapor' => $item->admin_reporter_name,
            'nik' => $item->resident?->nik ?? '-',
            'diajukan' => optional($item->created_at)->translatedFormat('d M Y, H.i') ?? '-',
            'deskripsi' => $item->description,
            'unit' => $item->unit,
            'catatan' => $item->handling_note,
            'tanggapan' => $item->response_note,
            'alasan' => $item->rejection_reason,
        ]);

    $selectedComplaint = $laporanList->firstWhere('kode', $selectedKode) ?? $laporanList->first();

    $unitOptions = config('units.options');
    $complaintSetting = \App\Models\ComplaintSetting::getSettings()->load('lastUpdatedByUser');

    return view('admin.dashboard', compact(
        'statusCards', 'laporanList', 'status', 'selectedKode',
        'selectedComplaint', 'unitOptions', 'complaintSetting', 'isUnit'
    ));
}
}
