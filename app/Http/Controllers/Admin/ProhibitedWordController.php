<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProhibitedWord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProhibitedWordController extends Controller
{
    public function index()
    {
        $kataTerlarang = ProhibitedWord::query()->orderBy('word')->pluck('word');

        return view('admin.kata-terlarang', compact('kataTerlarang'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'words' => 'sometimes|array|max:2000',
            'words.*' => 'required|string|max:50',
        ]);

        $words = collect($validated['words'] ?? [])
            ->map(fn (string $word) => trim($word))
            ->filter()
            ->unique(fn (string $word) => Str::lower($word))
            ->values();

        DB::transaction(function () use ($words) {
            ProhibitedWord::query()->delete();

            foreach ($words as $word) {
                ProhibitedWord::create(['word' => $word]);
            }
        });

        return redirect()->route('admin.kata-terlarang')
            ->with('success', 'Daftar kata terlarang berhasil disimpan.');
    }
}
