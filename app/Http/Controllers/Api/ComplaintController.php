<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ComplaintController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'title' => 'required',
            'description' => 'required',
            'location' => 'required',
            'category' => 'required',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $photoPath = null;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')
                ->store('complaints', 'public');
        }

        $complaint = Complaint::create([
            'ticket_code' => 'TKT-' . strtoupper(Str::random(8)),
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'category' => $request->category,
            'photo' => $photoPath,
            'status' => 'submitted'
        ]);

        return response()->json([
            'message' => 'Pengaduan berhasil dibuat',
            'ticket_code' => $complaint->ticket_code,
            'data' => $complaint
        ], 201);
    }

    public function show($ticket_code)
    {
        $complaint = Complaint::where(
            'ticket_code',
            $ticket_code
        )->first();

        if (!$complaint) {
            return response()->json([
                'message' => 'Tiket tidak ditemukan'
            ], 404);
        }

        return response()->json($complaint);
    }

    public function statistics()
    {
        return response()->json([
            'total' => Complaint::count(),
            'submitted' => Complaint::where('status', 'submitted')->count(),
            'verified' => Complaint::where('status', 'verified')->count(),
            'process' => Complaint::where('status', 'process')->count(),
            'completed' => Complaint::where('status', 'completed')->count(),
            'rejected' => Complaint::where('status', 'rejected')->count(),
        ]);
    }
}