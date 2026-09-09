<?php

namespace Tests\Feature;

use App\Models\Complaint;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UnitAccountTest extends TestCase
{
    use RefreshDatabase;

    protected function admin(): User
    {
        return User::factory()->create(['role' => User::ROLE_ADMIN]);
    }

    /**
     * Autentikasi lewat token Bearer sungguhan, seperti yang dilakukan
     * halaman admin. Sanctum::actingAs() menukar guard default ke "sanctum",
     * sehingga endpoint login yang memakai Auth::attempt() pada guard default
     * ikut rusak di dalam test.
     */
    protected function asUser(User $user): static
    {
        return $this->withToken($user->createToken('test')->plainTextToken);
    }

    protected function unitAccount(string $unit = 'Unit Kebersihan'): User
    {
        return User::factory()->unit($unit)->create();
    }

    public function test_halaman_akun_unit_tidak_lagi_menampilkan_data_dummy(): void
    {
        $this->unitAccount('Unit Kebersihan');

        $response = $this->get(route('admin.akun-unit'))->assertOk();

        // Daftar dimuat lewat API; data contoh yang dulu di-hardcode harus hilang.
        $response->assertDontSee('unit_infrastruktur@lurah.local')
            ->assertDontSee('11/6/2026')
            ->assertSee('Memuat daftar akun...', false);

        // Semua unit resmi tetap tersedia sebagai pilihan di form.
        foreach (config('units.options') as $unit) {
            $response->assertSee($unit, false);
        }
    }

    public function test_akun_unit_baru_tersimpan_ke_database(): void
    {
        $this->asUser($this->admin());

        $response = $this->postJson('/api/admin/unit-accounts', [
            'email' => 'unit_kebersihan@lurah.local',
            'password' => 'rahasia-unit',
            'unit' => 'Unit Kebersihan',
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.email', 'unit_kebersihan@lurah.local')
            ->assertJsonPath('data.unit', 'Unit Kebersihan');

        $this->assertDatabaseHas('users', [
            'email' => 'unit_kebersihan@lurah.local',
            'role' => User::ROLE_UNIT,
            'unit' => 'Unit Kebersihan',
        ]);

        $user = User::where('email', 'unit_kebersihan@lurah.local')->firstOrFail();
        $this->assertTrue(Hash::check('rahasia-unit', $user->password), 'kata sandi harus ter-hash');
    }

    public function test_akun_unit_baru_bisa_langsung_masuk(): void
    {
        $this->asUser($this->admin());

        $this->postJson('/api/admin/unit-accounts', [
            'email' => 'unit_keamanan@lurah.local',
            'password' => 'rahasia-unit',
            'unit' => 'Unit Keamanan',
        ])->assertCreated();

        $this->postJson('/api/admin/login', [
            'email' => 'unit_keamanan@lurah.local',
            'password' => 'rahasia-unit',
        ])->assertOk()->assertJsonStructure(['token']);
    }

    public function test_email_duplikat_ditolak(): void
    {
        $this->asUser($this->admin());
        $existing = $this->unitAccount();

        $this->postJson('/api/admin/unit-accounts', [
            'email' => $existing->email,
            'password' => 'rahasia-unit',
            'unit' => 'Unit Umum',
        ])->assertStatus(422)->assertJsonValidationErrors('email');

        $this->assertSame(1, User::unit()->count());
    }

    public function test_unit_di_luar_daftar_ditolak(): void
    {
        $this->asUser($this->admin());

        $this->postJson('/api/admin/unit-accounts', [
            'email' => 'unit_palsu@lurah.local',
            'password' => 'rahasia-unit',
            'unit' => 'Unit Tidak Terdaftar',
        ])->assertStatus(422)->assertJsonValidationErrors('unit');

        $this->assertDatabaseMissing('users', ['email' => 'unit_palsu@lurah.local']);
    }

    public function test_kata_sandi_minimal_delapan_karakter(): void
    {
        $this->asUser($this->admin());

        $this->postJson('/api/admin/unit-accounts', [
            'email' => 'unit_pendek@lurah.local',
            'password' => 'pendek',
            'unit' => 'Unit Umum',
        ])->assertStatus(422)->assertJsonValidationErrors('password');
    }

    public function test_daftar_memuat_semua_akun_dengan_label_peran(): void
    {
        $admin = $this->admin();
        $this->asUser($admin);
        $this->unitAccount('Unit Kebersihan');
        $this->unitAccount('Unit Keamanan');

        $response = $this->getJson('/api/admin/unit-accounts')->assertOk();

        // Admin ikut tampil supaya ada satu tempat melihat seluruh akun.
        $response->assertJsonCount(3, 'data')
            ->assertJsonPath('unit_options', config('units.options'));

        // Akun admin didahulukan, ditandai, dan tidak boleh punya aksi.
        $response->assertJsonPath('data.0.email', $admin->email)
            ->assertJsonPath('data.0.label', 'Admin')
            ->assertJsonPath('data.0.is_unit', false)
            ->assertJsonPath('data.0.unit', null);

        $response->assertJsonPath('data.1.label', 'Unit Kebersihan')
            ->assertJsonPath('data.1.is_unit', true);
    }

    public function test_daftar_menyertakan_identitas_akun_yang_sedang_masuk(): void
    {
        $admin = $this->admin();
        $this->asUser($admin);

        $this->getJson('/api/admin/unit-accounts')
            ->assertOk()
            ->assertJsonPath('current.id', $admin->id)
            ->assertJsonPath('current.email', $admin->email)
            ->assertJsonPath('current.name', $admin->name)
            ->assertJsonPath('current.label', 'Admin')
            ->assertJsonPath('current.is_unit', false);
    }

    public function test_halaman_memuat_kartu_akun_yang_sedang_masuk(): void
    {
        $this->get(route('admin.akun-unit'))
            ->assertOk()
            ->assertSee('Sedang masuk sebagai', false)
            ->assertSee('akun-saya-email', false)
            ->assertSee('akun-saya-peran', false)
            // Kartu diisi dari payload.current, bukan dari data yang di-render server.
            ->assertSee('renderCurrent(payload.current)', false);
    }

    public function test_markup_baris_hanya_membaca_data_dari_objek_account(): void
    {
        // Markup baris pernah memanggil helper todayFormatted() yang sudah
        // dihapus, sehingga daftar gagal dirender dengan
        // "todayFormatted is not defined". Setiap nilai harus datang dari API.
        $response = $this->get(route('admin.akun-unit'))->assertOk();

        $response->assertDontSee('todayFormatted', false)
            ->assertSee('account.dibuat', false)
            ->assertSee('optionsHtml(account.unit)', false);
    }

    public function test_halaman_menyembunyikan_aksi_pada_baris_akun_admin(): void
    {
        $this->asUser($this->admin());

        // Penanda yang dipakai JS untuk melepas tombol aksi di baris admin.
        $this->get(route('admin.akun-unit'))
            ->assertOk()
            ->assertSee('akun-actions', false)
            ->assertSee("if (!account.is_unit)", false);
    }

    public function test_unit_akun_dapat_dipindahkan(): void
    {
        $this->asUser($this->admin());
        $account = $this->unitAccount('Unit Kebersihan');

        $this->putJson("/api/admin/unit-accounts/{$account->id}", ['unit' => 'Unit Keamanan'])
            ->assertOk()
            ->assertJsonPath('data.unit', 'Unit Keamanan');

        $this->assertDatabaseHas('users', ['id' => $account->id, 'unit' => 'Unit Keamanan']);
    }

    public function test_akun_unit_dapat_dihapus(): void
    {
        $this->asUser($this->admin());
        $account = $this->unitAccount();

        $this->deleteJson("/api/admin/unit-accounts/{$account->id}")->assertOk();

        $this->assertDatabaseMissing('users', ['id' => $account->id]);
    }

    public function test_reset_kata_sandi_mengembalikan_sandi_baru_dan_menyimpannya(): void
    {
        $this->asUser($this->admin());
        $account = $this->unitAccount();
        $before = $account->password;

        $response = $this->postJson("/api/admin/unit-accounts/{$account->id}/reset-password")
            ->assertOk()
            ->assertJsonStructure(['message', 'password']);

        $password = $response->json('password');
        $account->refresh();

        $this->assertNotSame($before, $account->password);
        $this->assertTrue(Hash::check($password, $account->password));
    }

    public function test_endpoint_menolak_akun_admin_sebagai_sasaran(): void
    {
        $this->asUser($this->admin());
        $otherAdmin = $this->admin();

        $this->deleteJson("/api/admin/unit-accounts/{$otherAdmin->id}")->assertNotFound();
        $this->putJson("/api/admin/unit-accounts/{$otherAdmin->id}", ['unit' => 'Unit Umum'])->assertNotFound();

        $this->assertDatabaseHas('users', ['id' => $otherAdmin->id]);
    }

    public function test_tanpa_token_tidak_bisa_membuat_akun(): void
    {
        $this->postJson('/api/admin/unit-accounts', [
            'email' => 'penyusup@lurah.local',
            'password' => 'rahasia-unit',
            'unit' => 'Unit Umum',
        ])->assertUnauthorized();

        $this->getJson('/api/admin/unit-accounts')->assertUnauthorized();
        $this->assertDatabaseMissing('users', ['email' => 'penyusup@lurah.local']);
    }

    public function test_akun_unit_tidak_boleh_mengelola_akun_lain(): void
    {
        $this->asUser($this->unitAccount());

        $this->postJson('/api/admin/unit-accounts', [
            'email' => 'dibuat_unit@lurah.local',
            'password' => 'rahasia-unit',
            'unit' => 'Unit Umum',
        ])->assertForbidden();

        $this->getJson('/api/admin/unit-accounts')->assertForbidden();
        $this->assertDatabaseMissing('users', ['email' => 'dibuat_unit@lurah.local']);
    }

    public function test_navbar_menyembunyikan_menu_akun_unit_sampai_peran_diketahui(): void
    {
        // Halaman admin dirender tanpa sesi, jadi menu khusus admin harus
        // keluar dalam keadaan tersembunyi dan baru dibuka oleh skrip navbar
        // setelah /api/me memastikan pemakai token adalah admin. Kalau tidak,
        // akun unit sempat melihat menu ini di setiap muat halaman.
        $response = $this->get(route('admin.dashboard'))->assertOk();

        $response->assertSee('data-admin-only hidden', false)
            ->assertSee("fetch('/api/me'", false)
            ->assertSee("el.hidden = !isAdmin", false);
    }

    public function test_endpoint_identitas_mengembalikan_akun_pemilik_token(): void
    {
        $account = $this->unitAccount('Unit Keamanan');
        $this->asUser($account);

        $this->getJson('/api/me')
            ->assertOk()
            ->assertJsonPath('data.email', $account->email)
            ->assertJsonPath('data.role', User::ROLE_UNIT)
            ->assertJsonPath('data.label', 'Unit Keamanan')
            ->assertJsonPath('data.is_admin', false);
    }

    public function test_endpoint_identitas_butuh_token(): void
    {
        // Berbeda dari pengelolaan akun, endpoint ini terbuka untuk kedua
        // peran — yang dilarang hanya permintaan tanpa token.
        $this->getJson('/api/me')->assertUnauthorized();
    }

    public function test_login_mengembalikan_peran_akun(): void
    {
        $account = $this->unitAccount('Unit Umum');
        $account->update(['password' => 'rahasia-unit']);

        $this->postJson('/api/admin/login', [
            'email' => $account->email,
            'password' => 'rahasia-unit',
        ])
            ->assertOk()
            ->assertJsonPath('identity.role', User::ROLE_UNIT)
            ->assertJsonPath('identity.label', 'Unit Umum');
    }

    public function test_login_tetap_bekerja_setelah_permintaan_bertoken(): void
    {
        // Middleware "auth:sanctum" memindahkan guard default ke sanctum untuk
        // sisa daur hidup aplikasi. Login memakai attempt(), yang tidak ada di
        // RequestGuard milik sanctum, jadi urutan inilah yang dulu membuat
        // login gagal dengan "Method ...RequestGuard::attempt does not exist".
        $admin = $this->admin();
        $admin->update(['password' => 'rahasia-admin']);

        $this->asUser($admin)->getJson('/api/me')->assertOk();

        $this->postJson('/api/admin/login', [
            'email' => $admin->email,
            'password' => 'rahasia-admin',
        ])->assertOk()->assertJsonStructure(['token']);
    }

    public function test_disposisi_dashboard_menawarkan_semua_unit_resmi(): void
    {
        // Daftar unit di dashboard dulu digandakan sebagai konstanta di
        // controller. Setiap unit yang akunnya bisa dibuat harus juga bisa
        // menerima disposisi, jadi keduanya membaca config yang sama.
        Complaint::create([
            'ticket_code' => 'LO-260910-TEST',
            'title' => 'Jalan berlubang',
            'description' => 'Lubang di depan gang.',
            'location' => 'RT 01',
            'category' => 'Infrastruktur',
            'status' => 'verified',
        ]);

        $response = $this->get(route('admin.dashboard', ['status' => 'disposisi']))->assertOk();

        foreach (config('units.options') as $unit) {
            $response->assertSee('<option value="' . $unit . '"', false);
        }
    }
}
