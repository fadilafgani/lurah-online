<?php

namespace Tests\Feature;

use App\Models\Complaint;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RoleSeparationTest extends TestCase
{
    use RefreshDatabase;

    protected function createResident(array $attributes = []): Resident
    {
        return Resident::create(array_merge([
            'nik' => '3205010101900001',
            'name' => 'Warga Uji',
            'password' => Hash::make('password123'),
            'address' => 'Jl. Mawar No. 10',
            'rt' => '001',
            'rw' => '002',
            'status' => 'aktif',
            'must_change_password' => false,
        ], $attributes));
    }

    protected function createAdmin(array $attributes = []): User
    {
        return User::factory()->create(array_merge([
            'role' => User::ROLE_ADMIN,
            'password' => Hash::make('admin12345'),
        ], $attributes));
    }

    protected function createUnit(string $unit = 'Unit Kebersihan'): User
    {
        return User::factory()->unit($unit)->create([
            'password' => Hash::make('unit12345'),
        ]);
    }

    /**
     * Skenario 1: Pengguna belum login melihat pilihan login yang sesuai.
     */
    public function test_01_pengguna_belum_login_melihat_pilihan_login_yang_sesuai(): void
    {
        $response = $this->get('/')->assertOk();
        $response->assertSee('Masuk Warga');
        $response->assertSee(url('/admin'));

        $this->get(route('warga.login'))
            ->assertOk()
            ->assertSee('Masuk Warga')
            ->assertSee('nik')
            ->assertSee('Kata Sandi');

        $this->get(route('admin.login'))
            ->assertOk()
            ->assertSee('Masuk Admin/Unit')
            ->assertSee('email')
            ->assertSee('Password');
    }

    /**
     * Skenario 2: Warga berhasil login dan tombol login admin tidak lagi terlihat di seluruh navigasi warga.
     */
    public function test_02_warga_berhasil_login_dan_tombol_login_admin_tidak_lagi_terlihat_di_seluruh_navigasi_warga(): void
    {
        $resident = $this->createResident(['name' => 'Budi Santoso']);
        $this->actingAs($resident, 'resident');

        $routes = ['/', '/pengaduan', '/lacak', '/laporan'];

        foreach ($routes as $path) {
            $response = $this->get($path)->assertOk();
            $response->assertSee('Budi Santoso');
            $response->assertSee(route('warga.logout'));
            // Tombol admin tidak boleh dirender
            $response->assertDontSee('href="' . url('/admin') . '"', false);
            $response->assertDontSee('>Admin</a>', false);
        }
    }

    /**
     * Skenario 3: Admin berhasil login dan tombol login warga tidak lagi terlihat di seluruh navigasi admin.
     */
    public function test_03_admin_berhasil_login_dan_tombol_login_warga_tidak_lagi_terlihat_di_seluruh_navigasi_admin(): void
    {
        $admin = $this->createAdmin();
        $this->actingAs($admin, 'web');

        $adminRoutes = [
            route('admin.dashboard'),
            route('admin.laporan'),
            route('admin.akun-unit'),
            route('admin.warga'),
        ];

        foreach ($adminRoutes as $url) {
            $response = $this->get($url)->assertOk();
            // Navigasi admin tidak boleh memuat tautan login warga
            $response->assertDontSee(route('warga.login'));
            $response->assertDontSee('Masuk Warga');
        }
    }

    /**
     * Skenario 4: Warga membuka URL dashboard admin secara langsung: akses ditolak (HTTP 403).
     */
    public function test_04_warga_membuka_url_dashboard_admin_secara_langsung_akses_ditolak(): void
    {
        $resident = $this->createResident();
        $this->actingAs($resident, 'resident');

        $this->get(route('admin.dashboard'))->assertStatus(403);
        $this->get(route('admin.laporan'))->assertStatus(403);
        $this->get(route('admin.akun-unit'))->assertStatus(403);
        $this->get(route('admin.warga'))->assertStatus(403);
        $this->get(route('admin.kata-terlarang'))->assertStatus(403);
    }

    /**
     * Skenario 5: Warga memanggil API admin secara langsung: akses ditolak oleh backend (HTTP 403).
     */
    public function test_05_warga_memanggil_api_admin_secara_langsung_akses_ditolak_oleh_backend(): void
    {
        $resident = $this->createResident();
        $this->actingAs($resident, 'resident');

        $res1 = $this->getJson('/api/admin/unit-accounts');
        $this->assertTrue(in_array($res1->status(), [401, 403]), 'Akses API admin harus ditolak (401/403).');

        $res2 = $this->getJson('/api/admin/residents');
        $this->assertTrue(in_array($res2->status(), [401, 403]), 'Akses API admin harus ditolak (401/403).');

        $res3 = $this->postJson('/api/admin/residents', [
            'nik' => '3205010101909999',
            'name' => 'Warga Ilegal',
        ]);
        $this->assertTrue(in_array($res3->status(), [401, 403]), 'Akses API admin harus ditolak (401/403).');
    }

    /**
     * Skenario 6: Admin membuka route khusus warga yang memerlukan sesi warga: akses ditolak (HTTP 403).
     */
    public function test_06_admin_membuka_route_khusus_warga_yang_memerlukan_sesi_warga_akses_ditolak(): void
    {
        $admin = $this->createAdmin();
        $this->actingAs($admin, 'web');

        $this->get('/pengaduan')->assertStatus(403);
        $this->get(route('warga.ganti-password'))->assertStatus(403);
    }

    /**
     * Skenario 7: Warga mencoba login admin tanpa logout: ditolak (HTTP 403).
     */
    public function test_07_warga_mencoba_login_admin_tanpa_logout_ditolak(): void
    {
        $resident = $this->createResident();
        $this->actingAs($resident, 'resident');

        // Buka halaman login admin
        $this->get(route('admin.login'))->assertStatus(403);

        // Submit endpoint login admin
        $this->postJson('/api/admin/login', [
            'email' => 'admin@lurah.local',
            'password' => 'secret',
        ])->assertStatus(403);

        $this->post(route('admin.login.submit'), [
            'email' => 'admin@lurah.local',
            'password' => 'secret',
        ])->assertStatus(403);
    }

    /**
     * Skenario 8: Admin mencoba login warga tanpa logout: ditolak (HTTP 403).
     */
    public function test_08_admin_mencoba_login_warga_tanpa_logout_ditolak(): void
    {
        $admin = $this->createAdmin();
        $this->actingAs($admin, 'web');

        // Buka form login warga
        $this->get(route('warga.login'))->assertStatus(403);

        // Submit form login warga
        $this->post(route('warga.login.submit'), [
            'nik' => '3205010101900001',
            'password' => 'password123',
        ])->assertStatus(403);
    }

    /**
     * Skenario 9: Setelah logout, sesi lama tidak dapat digunakan untuk mengakses endpoint terlindungi.
     */
    public function test_09_setelah_logout_sesi_lama_tidak_dapat_digunakan_untuk_mengakses_endpoint_terlindungi(): void
    {
        // 1. Sesi warga
        $resident = $this->createResident();
        $this->actingAs($resident, 'resident');
        $this->get('/pengaduan')->assertOk();

        // Logout warga
        $this->post(route('warga.logout'))->assertRedirect('/');
        $this->assertGuest('resident');
        $this->get('/pengaduan')->assertRedirect(route('warga.login'));

        // 2. Sesi admin
        $admin = $this->createAdmin();
        $this->actingAs($admin, 'web');
        $this->get(route('admin.dashboard'))->assertOk();

        // Logout admin
        $this->post(route('admin.logout'))->assertRedirect(route('admin.login'));
        $this->assertGuest('web');
        $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
    }

    /**
     * Skenario 10: Refresh halaman tidak mengubah role pengguna.
     */
    public function test_10_refresh_halaman_tidak_mengubah_role_pengguna(): void
    {
        $resident = $this->createResident(['name' => 'Warga Tetap']);
        $this->actingAs($resident, 'resident');

        for ($i = 0; $i < 3; $i++) {
            $response = $this->get('/pengaduan')->assertOk();
            $response->assertSee('Warga Tetap');
            $this->assertTrue(auth('resident')->check());
            $this->assertFalse(auth('web')->check());
        }

        // Logout warga sesuai kebijakan satu role aktif
        $this->post(route('warga.logout'));

        $admin = $this->createAdmin(['email' => 'admin_tetap@lurah.local']);
        $this->actingAs($admin, 'web');

        for ($i = 0; $i < 3; $i++) {
            $response = $this->get(route('admin.dashboard'))->assertOk();
            $response->assertSee('Dashboard Admin');
            $this->assertTrue(auth('web')->check());
            $this->assertFalse(auth('resident')->check());
        }
    }

    /**
     * Skenario 11: Membuka kembali halaman melalui tombol Back tidak memberikan akses ke data terlindungi (No-Cache).
     */
    public function test_11_membuka_kembali_halaman_melalui_tombol_back_tidak_memberikan_akses_ke_data_terlindungi(): void
    {
        $admin = $this->createAdmin();
        $this->actingAs($admin, 'web');

        $resAdmin = $this->get(route('admin.dashboard'));
        $resAdmin->assertHeader('Cache-Control');
        $cacheHeaderAdmin = $resAdmin->headers->get('Cache-Control');
        $this->assertStringContainsString('no-store', $cacheHeaderAdmin);
        $this->assertStringContainsString('no-cache', $cacheHeaderAdmin);

        $resident = $this->createResident();
        $this->actingAs($resident, 'resident');

        $resResident = $this->get('/pengaduan');
        $resResident->assertHeader('Cache-Control');
        $cacheHeaderRes = $resResident->headers->get('Cache-Control');
        $this->assertStringContainsString('no-store', $cacheHeaderRes);
        $this->assertStringContainsString('no-cache', $cacheHeaderRes);
    }

    /**
     * Skenario 12: Role yang dimanipulasi pada frontend tidak mengubah hak akses backend.
     */
    public function test_12_role_yang_dimanipulasi_pada_frontend_tidak_mengubah_hak_akses_backend(): void
    {
        $resident = $this->createResident();
        $this->actingAs($resident, 'resident');

        // Manipulasi header atau body role
        $res = $this->withHeaders([
            'X-Role' => 'admin',
            'X-Admin' => 'true',
        ])->postJson('/api/admin/residents', [
            'role' => 'admin',
            'nik' => '3205010101908888',
            'name' => 'Penyusup',
        ]);
        $this->assertTrue(in_array($res->status(), [401, 403]));

        $this->withHeaders([
            'X-Role' => 'admin',
        ])->get(route('admin.dashboard'))->assertStatus(403);
    }

    /**
     * Skenario 13: Login, logout, dan navigasi tetap berfungsi setelah perubahan.
     */
    public function test_13_login_logout_dan_navigasi_tetap_berfungsi_setelah_perubahan(): void
    {
        $resident = $this->createResident([
            'nik' => '3205010101907777',
            'password' => Hash::make('rahasia77'),
        ]);

        // Login Warga via Form
        $resLogin = $this->post(route('warga.login.submit'), [
            'nik' => '3205010101907777',
            'password' => 'rahasia77',
        ]);
        $resLogin->assertRedirect(route('pengaduan.buat'));
        $this->assertAuthenticatedAs($resident, 'resident');

        // Navigasi form pengaduan
        $this->get(route('pengaduan.buat'))->assertOk();

        // Logout
        $this->post(route('warga.logout'))->assertRedirect('/');
        $this->assertGuest('resident');

        // Login Admin via API / Form
        $admin = $this->createAdmin([
            'email' => 'admin_flow@lurah.local',
            'password' => Hash::make('adminflow123'),
        ]);

        $resAdminLogin = $this->postJson('/api/admin/login', [
            'email' => 'admin_flow@lurah.local',
            'password' => 'adminflow123',
        ]);
        $resAdminLogin->assertOk()->assertJsonStructure(['token', 'identity']);
        $this->assertAuthenticatedAs($admin, 'web');

        // Navigasi dashboard
        $this->get(route('admin.dashboard'))->assertOk();

        // Logout Admin
        $this->post(route('admin.logout'))->assertRedirect(route('admin.login'));
        $this->assertGuest('web');
    }

    /**
     * Skenario 14: Fitur pengaduan, pelacakan tiket, manajemen warga, dan pengaturan lokasi tetap berjalan.
     */
    public function test_14_fitur_pengaduan_pelacakan_tiket_manajemen_warga_dan_pengaturan_lokasi_tetap_berjalan(): void
    {
        Storage::fake('public');

        $resident = $this->createResident([
            'nik' => '3205010101906666',
            'name' => 'Siti Aminah',
        ]);
        $this->actingAs($resident, 'resident');

        // Buat pengaduan
        $resSimpan = $this->post(route('pengaduan.simpan'), [
            'judul' => 'Lampu Jalan Mati',
            'kategori' => 'Penerangan Jalan',
            'deskripsi' => 'Lampu penerangan jalan padam di RT 01',
            'lokasi' => 'Jl. Kenanga Depan Pos',
            'foto' => UploadedFile::fake()->image('lampu.jpg'),
            'anonim' => false,
        ]);

        $resSimpan->assertRedirect();
        $complaint = Complaint::where('resident_id', $resident->id)->first();
        $this->assertNotNull($complaint);
        $this->assertEquals('Lampu Jalan Mati', $complaint->title);

        // Logout warga sebelum akses publik / admin
        $this->post(route('warga.logout'));

        // Lacak tiket secara publik
        $this->get(route('lacak.cari', ['kode' => $complaint->ticket_code]))
            ->assertOk()
            ->assertSee($complaint->ticket_code)
            ->assertSee('Diajukan');

        // Admin login & memproses pengaduan
        $admin = $this->createAdmin();
        $this->actingAs($admin, 'web');

        $resDisposisi = $this->put(route('admin.laporan.status', ['complaint' => $complaint->ticket_code]), [
            'status' => 'verified',
            'unit' => 'Unit Infrastruktur',
        ]);
        $resDisposisi->assertRedirect();
        $complaint->refresh();
        $this->assertEquals('verified', $complaint->status);
        $this->assertEquals('Unit Infrastruktur', $complaint->unit);
    }
}
