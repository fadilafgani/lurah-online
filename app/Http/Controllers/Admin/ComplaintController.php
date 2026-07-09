<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    private const STATUS_TABS = [
        'submitted' => 'verifikasi',
        'verified' => 'disposisi',
        'process' => 'penanganan',
        'completed' => 'selesai',
        'rejected' => 'ditolak',
    ];

    public function index()
    {
        $complaints = Complaint::latest()->get();

        return view('admin.laporan', compact('complaints'));
    }

    public function updateStatus(Request $request, Complaint $complaint)
    {
        $request->validate([
            'status' => 'required|in:submitted,verified,process,completed,rejected',
            'unit' => 'nullable|string|max:255',
            'rejection_reason' => 'nullable|string',
        ]);

        $complaint->update([
            'status' => $request->status,
            ...($request->filled('unit') ? ['unit' => $request->unit] : []),
            ...($request->status === 'rejected' ? ['rejection_reason' => $request->rejection_reason] : []),
        ]);

        return redirect()->route('admin.dashboard', [
            'status' => self::STATUS_TABS[$request->status],
            'kode' => $complaint->ticket_code,
        ])->with('success', 'Status berhasil diperbarui');
    }

    public function saveHandling(Request $request, Complaint $complaint)
    {
        $request->validate([
            'catatan' => 'nullable|string',
            'tanggapan' => 'nullable|string',
        ]);

        $complaint->update([
            'handling_note' => $request->catatan,
            'response_note' => $request->tanggapan,
        ]);

        return redirect()->route('admin.dashboard', [
            'status' => 'penanganan',
            'kode' => $complaint->ticket_code,
        ])->with('success', 'Catatan berhasil disimpan');
    }

    public function complete(Request $request, Complaint $complaint)
    {
        $request->validate([
            'catatan' => 'nullable|string',
            'tanggapan' => 'nullable|string',
            'foto_hasil' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $resultPhotoPath = $complaint->result_photo;

        if ($request->hasFile('foto_hasil')) {
            $resultPhotoPath = $request->file('foto_hasil')->store('complaints', 'public');
        }

        $complaint->update([
            'status' => 'completed',
            'handling_note' => $request->catatan,
            'response_note' => $request->tanggapan,
            'result_photo' => $resultPhotoPath,
        ]);

        return redirect()->route('admin.dashboard', [
            'status' => 'selesai',
            'kode' => $complaint->ticket_code,
        ])->with('success', 'Pengaduan berhasil diselesaikan');
    }

    public function destroy(string $ticketCode)
    {
        Complaint::where('ticket_code', $ticketCode)->delete();

        return back()->with('success', 'Pengaduan berhasil dihapus secara permanen');
    }
}
