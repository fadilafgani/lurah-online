<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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