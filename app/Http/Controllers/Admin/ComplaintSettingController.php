<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ComplaintSetting;
use Illuminate\Http\Request;

class ComplaintSettingController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'is_daily_limit_active' => 'required|in:0,1,true,false',
            'max_daily_complaints' => 'required|integer|min:1',
        ], [
            'is_daily_limit_active.required' => 'Status pembatasan wajib dipilih.',
            'max_daily_complaints.required' => 'Maksimal pengaduan per hari wajib diisi.',
            'max_daily_complaints.integer' => 'Batas pengaduan harus berupa angka bulat positif.',
            'max_daily_complaints.min' => 'Batas pengaduan harian minimal adalah 1 pengaduan.',
        ]);

        $setting = ComplaintSetting::getSettings();
        $setting->update([
            'is_daily_limit_active' => $request->boolean('is_daily_limit_active'),
            'max_daily_complaints' => (int) $request->input('max_daily_complaints'),
            'last_updated_by_user_id' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Pengaturan batas pengaduan harian berhasil diperbarui.');
    }

    public function show()
    {
        $setting = ComplaintSetting::getSettings()->load('lastUpdatedByUser:id,name,email');

        return response()->json([
            'status' => 'success',
            'data' => [
                'is_daily_limit_active' => (bool) $setting->is_daily_limit_active,
                'max_daily_complaints' => (int) $setting->max_daily_complaints,
                'last_updated_by' => $setting->lastUpdatedByUser?->name,
                'updated_at' => optional($setting->updated_at)->timezone('Asia/Jakarta')->translatedFormat('d M Y, H.i') . ' WIB',
            ],
        ]);
    }

    public function updateApi(Request $request)
    {
        $validated = $request->validate([
            'is_daily_limit_active' => 'required|boolean',
            'max_daily_complaints' => 'required|integer|min:1',
        ], [
            'is_daily_limit_active.required' => 'Status pembatasan wajib dipilih.',
            'is_daily_limit_active.boolean' => 'Status pembatasan harus bernilai true atau false.',
            'max_daily_complaints.required' => 'Maksimal pengaduan per hari wajib diisi.',
            'max_daily_complaints.integer' => 'Batas pengaduan harus berupa angka bulat positif.',
            'max_daily_complaints.min' => 'Batas pengaduan harian minimal adalah 1 pengaduan.',
        ]);

        $setting = ComplaintSetting::getSettings();
        $setting->update([
            'is_daily_limit_active' => $request->boolean('is_daily_limit_active'),
            'max_daily_complaints' => (int) $request->input('max_daily_complaints'),
            'last_updated_by_user_id' => auth()->id(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pengaturan batas pengaduan harian berhasil diperbarui.',
            'data' => [
                'is_daily_limit_active' => (bool) $setting->is_daily_limit_active,
                'max_daily_complaints' => (int) $setting->max_daily_complaints,
                'last_updated_by' => $setting->lastUpdatedByUser?->name,
                'updated_at' => optional($setting->updated_at)->timezone('Asia/Jakarta')->translatedFormat('d M Y, H.i') . ' WIB',
            ],
        ]);
    }
}
