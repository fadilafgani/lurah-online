<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UnitAccountController extends Controller
{
    /**
     * Daftar akun unit.
     */
    public function index(Request $request): JsonResponse
    {
        return response()->json([
            // Semua akun ditampilkan agar admin punya satu tempat melihatnya;
            // "admin" mendahului "unit" secara alfabet, jadi admin di atas.
            'data' => User::orderBy('role')->orderBy('id')->get()->map($this->present())->all(),
            // Identitas pemakai token, dikirim bersama daftar supaya halaman
            // tidak perlu permintaan kedua hanya untuk kartu "sedang masuk".
            'current' => ($this->present())($request->user()),
            'unit_options' => config('units.options'),
        ]);
    }

    /**
     * Buat akun unit baru.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8'],
            'unit' => ['required', 'string', Rule::in(config('units.options'))],
        ]);

        $user = User::create([
            // Form hanya meminta email, sandi, dan unit; nama akun mengikuti unit.
            'name' => $data['unit'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => User::ROLE_UNIT,
            'unit' => $data['unit'],
        ]);

        return response()->json([
            'message' => 'Akun unit berhasil dibuat.',
            'data' => ($this->present())($user),
        ], 201);
    }

    /**
     * Pindahkan akun ke unit lain.
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $this->abortIfNotUnitAccount($user);

        $data = $request->validate([
            'unit' => ['required', 'string', Rule::in(config('units.options'))],
        ]);

        $user->update([
            'name' => $data['unit'],
            'unit' => $data['unit'],
        ]);

        return response()->json([
            'message' => 'Unit berhasil diperbarui.',
            'data' => ($this->present())($user),
        ]);
    }

    /**
     * Terbitkan kata sandi sementara.
     *
     * Email unit umumnya internal (mis. @lurah.local) dan belum tentu bisa
     * menerima surat, jadi sandi baru dikembalikan sekali ke admin untuk
     * diserahkan langsung — bukan dikirim sebagai tautan reset.
     */
    public function resetPassword(User $user): JsonResponse
    {
        $this->abortIfNotUnitAccount($user);

        $password = Str::password(12, symbols: false);

        $user->update(['password' => $password]);
        $user->tokens()->delete();

        return response()->json([
            'message' => 'Kata sandi berhasil direset.',
            'password' => $password,
            'data' => ($this->present())($user),
        ]);
    }

    /**
     * Hapus akun unit.
     */
    public function destroy(User $user): JsonResponse
    {
        $this->abortIfNotUnitAccount($user);

        $user->tokens()->delete();
        $user->delete();

        return response()->json(['message' => 'Akun unit berhasil dihapus.']);
    }

    /**
     * Endpoint ini hanya boleh menyentuh akun unit, bukan akun admin.
     */
    protected function abortIfNotUnitAccount(User $user): void
    {
        abort_unless($user->isUnit(), 404);
    }

    protected function present(): callable
    {
        return fn (User $user): array => $user->toDisplayArray();
    }
}
