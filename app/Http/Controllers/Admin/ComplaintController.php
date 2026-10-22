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

    /** Hanya admin (akun non-unit). */
    private function adminOnly(Request $request): void
    {
        abort_if($request->user()->role === 'unit', 403, 'Hanya admin yang dapat melakukan aksi ini.');
    }

    /** Admin bebas; akun unit hanya untuk laporan unitnya yang sedang diproses. */
    private function authorizeHandling(Request $request, Complaint $complaint): void
    {
        $user = $request->user();

        if ($user->role === 'unit') {
            abort_unless(
                filled($user->unit)
                    && $complaint->unit === $user->unit
                    && $complaint->status === 'process',
                403,
                'Laporan ini bukan milik unit Anda.'
            );
        }
    }

    public function index(Request $request)
    {
        $this->adminOnly($request);

        $complaints = Complaint::with('resident')->latest()->get();

        return view('admin.laporan', compact('complaints'));
    }

    public function updateStatus(Request $request, Complaint $complaint)
    {
        $this->adminOnly($request);

        $request->validate([
            'status' => 'required|in:submitted,verified,process,completed,rejected',
            'unit' => 'nullable|string|max:255|in:' . implode(',', config('units.options')),
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
        $this->authorizeHandling($request, $complaint);   // <- bukan adminOnly

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
        $this->authorizeHandling($request, $complaint);   // <- bukan adminOnly

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

    public function destroy(Request $request, string $ticketCode)
    {
        $this->adminOnly($request);

        Complaint::where('ticket_code', $ticketCode)->delete();

        return back()->with('success', 'Pengaduan berhasil dihapus secara permanen');
    }
}