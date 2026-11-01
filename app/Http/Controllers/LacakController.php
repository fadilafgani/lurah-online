<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Complaint;

class LacakController extends Controller
{
    /**
     * Cari tiket berdasarkan kode
     */
    public function cari(Request $request)
    {
        $request->validate(['kode' => 'required|string']);

        $tiket = Complaint::where('ticket_code', $request->kode)->first();

        if (!$tiket) {
            return view('lacak', ['notFound' => true]);
        }

        return view('lacak', compact('tiket'));
    }

    /**
     * Tampilkan / unduh foto bukti pengaduan dengan validasi keberadaan file.
     */
    public function fotoBukti(Request $request, string $ticket_code)
    {
        $complaint = Complaint::where('ticket_code', $ticket_code)->firstOrFail();

        if (!$complaint->photo || !Storage::disk('public')->exists($complaint->photo)) {
            abort(404, 'Foto bukti pengaduan tidak ditemukan.');
        }

        return response()->file(Storage::disk('public')->path($complaint->photo));
    }

    /**
     * Tampilkan / unduh foto hasil penanganan dengan pemeriksaan hak akses dan aturan publikasi.
     */
    public function fotoHasil(Request $request, string $ticket_code)
    {
        $complaint = Complaint::where('ticket_code', $ticket_code)->firstOrFail();

        $user = Auth::guard('web')->user();
        $resident = Auth::guard('resident')->user();

        if (!$complaint->isResultPhotoVisibleTo($user, $resident)) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk melihat foto hasil penanganan laporan ini.');
        }

        if (!$complaint->result_photo || !Storage::disk('public')->exists($complaint->result_photo)) {
            abort(404, 'Foto hasil penanganan belum tersedia.');
        }

        return response()->file(Storage::disk('public')->path($complaint->result_photo));
    }

    /**
     * Simpan / perbarui rating & komentar untuk tiket yang sudah selesai.
     */
    public function nilai(Request $request, string $ticket_code)
    {
        $tiket = Complaint::where('ticket_code', $ticket_code)
            ->where('status', 'completed')
            ->firstOrFail();

        $validated = $request->validate([
            'rating'  => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $tiket->update([
            'rating'         => $validated['rating'],
            'rating_comment' => $validated['comment'] ?? null,
        ]);

        return redirect()
            ->route('lacak.cari', ['kode' => $ticket_code])
            ->with('success', 'Terima kasih atas penilaian Anda.');
    }

    /**
     * Buka kembali tiket yang sudah selesai (kembalikan status ke "process").
     */
    public function bukaKembali(Request $request, string $ticket_code)
    {
        $tiket = Complaint::where('ticket_code', $ticket_code)
            ->where('status', 'completed')
            ->firstOrFail();

        $tiket->update([
            'status' => 'process',
        ]);

        return redirect()
            ->route('lacak.cari', ['kode' => $ticket_code])
            ->with('success', 'Laporan dibuka kembali dan akan diproses ulang.');
    }
}