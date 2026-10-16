<?php

namespace Tests\Feature;

use App\Models\Complaint;
use App\Models\ComplaintSetting;
use App\Models\Resident;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DailyComplaintLimitTest extends TestCase
{
    use RefreshDatabase;

    private Resident $residentA;
    private Resident $residentB;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        // Pastikan setting awal aktif dengan limit 2
        ComplaintSetting::truncate();
        ComplaintSetting::create([
            'is_daily_limit_active' => true,
            'max_daily_complaints' => 2,
        ]);

        $this->residentA = Resident::create([
            'nik' => '3205012345670001',
            'name' => 'Warga Pertama',
            'address' => 'RT 001 RW 001',
            'rt' => '001',
            'rw' => '001',
            'status' => 'aktif',
            'password' => bcrypt('password123'),
            'must_change_password' => false,
        ]);

        $this->residentB = Resident::create([
            'nik' => '3205012345670002',
            'name' => 'Warga Kedua',
            'address' => 'RT 002 RW 001',
            'rt' => '002',
            'rw' => '001',
            'status' => 'aktif',
            'password' => bcrypt('password123'),
            'must_change_password' => false,
        ]);

        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);
    }

    private function actAsAdmin(): self
    {
        Auth::guard('resident')->logout();
        $this->flushSession();
        return $this->actingAs($this->admin, 'web');
    }

    private function submitComplaint(Resident $resident, string $title = 'Laporan Sampah'): \Illuminate\Testing\TestResponse
    {
        Auth::guard('web')->logout();
        $this->flushSession();
        return $this->actingAs($resident, 'resident')->post(route('pengaduan.simpan'), [
            'judul' => $title,
            'kategori' => 'Kebersihan & Sampah',
            'deskripsi' => 'Deskripsi kejadian rinci...',
            'lokasi' => 'RT 01 RW 01',
            'foto' => UploadedFile::fake()->image('bukti.jpg'),
            'anonim' => false,
        ]);
    }

    public function test_01_warga_belum_membuat_pengaduan_pengaduan_pertama_berhasil(): void
    {
        $response = $this->submitComplaint($this->residentA, 'Laporan Pertama');

        $response->assertStatus(302);
        $this->assertDatabaseHas('complaints', [
            'resident_id' => $this->residentA->id,
            'title' => 'Laporan Pertama',
        ]);
    }

    public function test_02_warga_sudah_membuat_satu_pengaduan_kedua_berhasil(): void
    {
        $this->submitComplaint($this->residentA, 'Laporan 1');
        $response = $this->submitComplaint($this->residentA, 'Laporan 2');

        $response->assertStatus(302);
        $this->assertEquals(2, Complaint::where('resident_id', $this->residentA->id)->count());
    }

    public function test_03_warga_sudah_membuat_dua_pengaduan_ketiga_ditolak(): void
    {
        $this->submitComplaint($this->residentA, 'Laporan 1');
        $this->submitComplaint($this->residentA, 'Laporan 2');

        $response = $this->submitComplaint($this->residentA, 'Laporan 3');

        $response->assertSessionHasErrors('kuota');
        $this->assertEquals(2, Complaint::where('resident_id', $this->residentA->id)->count());
        $this->assertDatabaseMissing('complaints', [
            'resident_id' => $this->residentA->id,
            'title' => 'Laporan 3',
        ]);
    }

    public function test_04_warga_lain_tetap_memiliki_kuota_masing_masing(): void
    {
        // Warga A menghabiskan kuotanya (2 pengaduan)
        $this->submitComplaint($this->residentA, 'Laporan A1');
        $this->submitComplaint($this->residentA, 'Laporan A2');
        $this->assertEquals(2, Complaint::where('resident_id', $this->residentA->id)->count());

        // Warga B tetap dapat membuat pengaduan
        $responseB1 = $this->submitComplaint($this->residentB, 'Laporan B1');
        $responseB1->assertStatus(302);

        $responseB2 = $this->submitComplaint($this->residentB, 'Laporan B2');
        $responseB2->assertStatus(302);

        $this->assertEquals(2, Complaint::where('resident_id', $this->residentB->id)->count());
    }

    public function test_05_admin_mengubah_batas_menjadi_3_warga_dapat_membuat_hingga_3_pengaduan(): void
    {
        $this->actAsAdmin()->post(route('admin.pengaturan.batas-harian'), [
            'is_daily_limit_active' => '1',
            'max_daily_complaints' => 3,
        ]);

        $this->submitComplaint($this->residentA, 'Laporan 1');
        $this->submitComplaint($this->residentA, 'Laporan 2');
        $response3 = $this->submitComplaint($this->residentA, 'Laporan 3');

        $response3->assertStatus(302);
        $this->assertEquals(3, Complaint::where('resident_id', $this->residentA->id)->count());

        // Pengaduan ke-4 harus ditolak
        $response4 = $this->submitComplaint($this->residentA, 'Laporan 4');
        $response4->assertSessionHasErrors('kuota');
        $this->assertEquals(3, Complaint::where('resident_id', $this->residentA->id)->count());
    }

    public function test_06_admin_mengubah_batas_menjadi_1_warga_yang_sudah_buat_satu_langsung_terkunci(): void
    {
        $this->submitComplaint($this->residentA, 'Laporan Pertama');

        // Admin mengubah batas menjadi 1
        $this->actAsAdmin()->post(route('admin.pengaturan.batas-harian'), [
            'is_daily_limit_active' => '1',
            'max_daily_complaints' => 1,
        ]);

        // Pengaduan kedua ditolak karena batas sekarang 1 dan warga sudah buat 1
        $response2 = $this->submitComplaint($this->residentA, 'Laporan Kedua');
        $response2->assertSessionHasErrors('kuota');
        $this->assertEquals(1, Complaint::where('resident_id', $this->residentA->id)->count());
    }

    public function test_07_admin_menonaktifkan_pembatasan_kuota_harian_tidak_lagi_diberlakukan(): void
    {
        // Admin menonaktifkan pembatasan
        $this->actAsAdmin()->post(route('admin.pengaturan.batas-harian'), [
            'is_daily_limit_active' => '0',
            'max_daily_complaints' => 2,
        ]);

        // Warga dapat membuat lebih dari batas lama
        $this->submitComplaint($this->residentA, 'Laporan 1')->assertStatus(302);
        $this->submitComplaint($this->residentA, 'Laporan 2')->assertStatus(302);
        $this->submitComplaint($this->residentA, 'Laporan 3')->assertStatus(302);
        $this->submitComplaint($this->residentA, 'Laporan 4')->assertStatus(302);

        $this->assertEquals(4, Complaint::where('resident_id', $this->residentA->id)->count());
    }

    public function test_08_pergantian_tanggal_mengikuti_zona_waktu_asia_jakarta(): void
    {
        // Set waktu sekarang: 15 Oktober 2026 jam 23.00 WIB
        Carbon::setTestNow(Carbon::parse('2026-10-15 23:00:00', 'Asia/Jakarta'));

        $this->submitComplaint($this->residentA, 'Laporan Malam')->assertStatus(302);
        $this->submitComplaint($this->residentA, 'Laporan Malam 2')->assertStatus(302);

        // Pengaduan ketiga pada hari yang sama ditolak
        $this->submitComplaint($this->residentA, 'Laporan Malam 3')->assertSessionHasErrors('kuota');

        // Pindah waktu melewati tengah malam: 16 Oktober 2026 jam 00.05 WIB
        Carbon::setTestNow(Carbon::parse('2026-10-16 00:05:00', 'Asia/Jakarta'));

        // Kuota telah di-reset pada hari baru WIB
        $responseHariBaru = $this->submitComplaint($this->residentA, 'Laporan Dini Hari');
        $responseHariBaru->assertStatus(302);
        $this->assertDatabaseHas('complaints', [
            'resident_id' => $this->residentA->id,
            'title' => 'Laporan Dini Hari',
        ]);

        Carbon::setTestNow(); // reset mocking
    }

    public function test_09_pengaduan_yang_gagal_disimpan_tidak_mengurangi_kuota(): void
    {
        // Request gagal validasi (tanpa foto)
        $responseGagal = $this->actingAs($this->residentA, 'resident')->post(route('pengaduan.simpan'), [
            'judul' => 'Laporan Cacat',
            'kategori' => 'Infrastruktur Jalan',
            'deskripsi' => 'Deskripsi...',
            'lokasi' => 'RT 01',
            // Tanpa foto
        ]);
        $responseGagal->assertSessionHasErrors('foto');

        // Kuota warga A masih utuh 2
        $this->submitComplaint($this->residentA, 'Laporan Sah 1')->assertStatus(302);
        $this->submitComplaint($this->residentA, 'Laporan Sah 2')->assertStatus(302);
        $this->assertEquals(2, Complaint::where('resident_id', $this->residentA->id)->count());
    }

    public function test_10_pengaduan_yang_dihapus_tetap_menghitung_pemakaian_kuota(): void
    {
        $this->submitComplaint($this->residentA, 'Laporan 1')->assertStatus(302);
        $this->submitComplaint($this->residentA, 'Laporan 2')->assertStatus(302);

        $firstComplaint = Complaint::where('title', 'Laporan 1')->first();
        $this->assertNotNull($firstComplaint);

        // Hapus pengaduan oleh admin
        $this->actAsAdmin()->delete(route('admin.laporan.destroy', $firstComplaint->ticket_code))->assertStatus(302);

        // Pengaduan telah berstatus soft delete
        $this->assertSoftDeleted('complaints', ['id' => $firstComplaint->id]);

        // Kuota tetap terhitung dipakai (tidak di-refund)
        $responseKe3 = $this->submitComplaint($this->residentA, 'Laporan Pengganti');
        $responseKe3->assertSessionHasErrors('kuota');
    }

    public function test_11_pemanggilan_api_langsung_tunduk_pada_kuota_pengaduan(): void
    {
        $this->submitComplaint($this->residentA, 'Laporan Web 1')->assertStatus(302);
        $this->submitComplaint($this->residentA, 'Laporan Web 2')->assertStatus(302);

        // Request ke-3 via API langsung dengan JSON header
        $responseApi = $this->actingAs($this->residentA, 'resident')
            ->postJson(route('pengaduan.simpan'), [
                'judul' => 'Laporan API 3',
                'kategori' => 'Infrastruktur Jalan',
                'deskripsi' => 'Testing API limit...',
                'lokasi' => 'RT 01',
                'foto' => UploadedFile::fake()->image('api.jpg'),
                'anonim' => false,
            ]);

        $responseApi->assertStatus(422);
        $responseApi->assertJsonStructure(['message', 'max_limit', 'used_today']);
        $responseApi->assertJsonFragment(['max_limit' => 2, 'used_today' => 2]);
    }

    public function test_12_admin_dashboard_mencatat_admin_pengubah_dan_menolak_nilai_invalid(): void
    {
        // 1. Sukses update dan rekam admin pengubah
        $response = $this->actAsAdmin()->post(route('admin.pengaturan.batas-harian'), [
            'is_daily_limit_active' => '1',
            'max_daily_complaints' => 5,
        ]);
        $response->assertSessionHas('success');

        $setting = ComplaintSetting::first();
        $this->assertEquals(5, $setting->max_daily_complaints);
        $this->assertEquals($this->admin->id, $setting->last_updated_by_user_id);

        // 2. Tolak nilai nol
        $this->actAsAdmin()->post(route('admin.pengaturan.batas-harian'), [
            'is_daily_limit_active' => '1',
            'max_daily_complaints' => 0,
        ])->assertSessionHasErrors('max_daily_complaints');

        // 3. Tolak nilai negatif
        $this->actAsAdmin()->post(route('admin.pengaturan.batas-harian'), [
            'is_daily_limit_active' => '1',
            'max_daily_complaints' => -3,
        ])->assertSessionHasErrors('max_daily_complaints');

        // 4. Tolak nilai pecahan / non-integer
        $this->actAsAdmin()->post(route('admin.pengaturan.batas-harian'), [
            'is_daily_limit_active' => '1',
            'max_daily_complaints' => 2.5,
        ])->assertSessionHasErrors('max_daily_complaints');

        // 5. Tolak nilai kosong
        $this->actAsAdmin()->post(route('admin.pengaturan.batas-harian'), [
            'is_daily_limit_active' => '1',
            'max_daily_complaints' => '',
        ])->assertSessionHasErrors('max_daily_complaints');
    }

    public function test_13_form_pengaduan_menampilkan_info_kuota_aktual_dan_menonaktifkan_submit_saat_habis(): void
    {
        // Saat kuota masih ada (0 dari 2)
        $res1 = $this->actingAs($this->residentA, 'resident')->get(route('pengaduan.buat'));
        $res1->assertStatus(200);
        $res1->assertSee('Batas pengaduan harian:');
        $res1->assertSee('2 pengaduan');
        $res1->assertSee('Pengaduan hari ini: 0 dari 2');
        $res1->assertSee('Sisa kuota: 2');
        $res1->assertDontSee('Batas Kuota Tercapai');

        // Buat 2 pengaduan hingga kuota habis
        $this->submitComplaint($this->residentA, 'Lap 1');
        $this->submitComplaint($this->residentA, 'Lap 2');

        // Buka form pengaduan saat kuota habis
        $res2 = $this->actingAs($this->residentA, 'resident')->get(route('pengaduan.buat'));
        $res2->assertStatus(200);
        $res2->assertSee('Pengaduan hari ini: 2 dari 2');
        $res2->assertSee('Batas Kuota Tercapai');
        $res2->assertSee('Anda telah mencapai batas pengaduan harian.');
        $res2->assertSee('disabled', false);
    }
}
