<?php

namespace Tests\Feature;

use App\Models\Complaint;
use App\Models\Resident;
use App\Models\User;
use App\Services\ResidentSpreadsheetService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ResidentManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function admin(): User
    {
        return User::factory()->create(['role' => User::ROLE_ADMIN]);
    }

    protected function unitAccount(): User
    {
        return User::factory()->create(['role' => User::ROLE_UNIT, 'unit' => 'Unit Kebersihan']);
    }

    protected function asUser(User $user): static
    {
        return $this->withToken($user->createToken('test')->plainTextToken);
    }

    /**
     * Skenario 11: Admin berhasil menambahkan data warga secara manual.
     */
    public function test_admin_berhasil_menambahkan_data_warga(): void
    {
        $admin = $this->admin();

        $response = $this->asUser($admin)->postJson('/api/admin/residents', [
            'nik' => '3205010101900001',
            'name' => 'Ahmad Hidayat',
            'address' => 'Jl. Kenanga No. 12',
            'rt' => '001',
            'rw' => '002',
            'status' => 'aktif',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('residents', [
            'nik' => '3205010101900001',
            'name' => 'Ahmad Hidayat',
            'status' => 'aktif',
            'must_change_password' => 1,
        ]);

        $resident = Resident::where('nik', '3205010101900001')->first();
        // Password awal di-hash dari NIK
        $this->assertTrue(Hash::check('3205010101900001', $resident->password));
    }

    /**
     * Skenario 12: NIK duplikat ditolak.
     */
    public function test_nik_duplikat_ditolak(): void
    {
        $admin = $this->admin();
        Resident::create([
            'nik' => '3205010101900001',
            'name' => 'Warga Lama',
            'status' => 'aktif',
            'password' => Hash::make('3205010101900001'),
        ]);

        $response = $this->asUser($admin)->postJson('/api/admin/residents', [
            'nik' => '3205010101900001',
            'name' => 'Warga Baru NIK Sama',
            'status' => 'aktif',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors('nik');
    }

    /**
     * Skenario 13: Field wajib divalidasi.
     */
    public function test_field_wajib_divalidasi(): void
    {
        $admin = $this->admin();

        $response = $this->asUser($admin)->postJson('/api/admin/residents', [
            'nik' => '',
            'name' => '',
            'status' => 'status_invalid',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['nik', 'name', 'status']);
    }

    /**
     * Skenario 14: Admin dapat mengedit data dan menonaktifkan akun.
     */
    public function test_admin_dapat_mengedit_data_dan_menonaktifkan_akun(): void
    {
        $admin = $this->admin();
        $resident = Resident::create([
            'nik' => '3205010101900002',
            'name' => 'Rina Wijaya',
            'address' => 'Alamat Lama',
            'status' => 'aktif',
            'password' => Hash::make('rahasia123'),
        ]);

        // Edit data
        $responseEdit = $this->asUser($admin)->putJson("/api/admin/residents/{$resident->id}", [
            'name' => 'Rina Wijaya S.Pd',
            'address' => 'Alamat Baru No. 44',
            'rt' => '003',
            'rw' => '005',
            'status' => 'aktif',
        ]);

        $responseEdit->assertOk();
        $resident->refresh();
        $this->assertEquals('Rina Wijaya S.Pd', $resident->name);
        $this->assertEquals('Alamat Baru No. 44', $resident->address);
        // Password tidak boleh tereset saat update data
        $this->assertTrue(Hash::check('rahasia123', $resident->password));

        // Toggle status menjadi nonaktif
        $responseToggle = $this->asUser($admin)->putJson("/api/admin/residents/{$resident->id}/status");
        $responseToggle->assertOk();
        $resident->refresh();
        $this->assertEquals('nonaktif', $resident->status);
    }

    /**
     * Skenario 15: Pengaduan historis tetap terhubung dan dapat diakses.
     */
    public function test_pengaduan_historis_tetap_terhubung_dan_dapat_diakses(): void
    {
        $resident = Resident::create([
            'nik' => '3205010101900003',
            'name' => 'Bambang',
            'status' => 'aktif',
            'password' => Hash::make('3205010101900003'),
        ]);

        $complaint = Complaint::create([
            'ticket_code' => 'LO-TEST-0001',
            'resident_id' => $resident->id,
            'name' => $resident->name,
            'title' => 'Pengaduan Lama',
            'description' => 'Isi laporan terdahulu',
            'location' => 'Banjarsari',
            'category' => 'Lainnya',
            'status' => 'completed',
        ]);

        // Nonaktifkan warga
        $resident->update(['status' => 'nonaktif']);

        $complaint->refresh();
        $this->assertEquals($resident->id, $complaint->resident_id);
        $this->assertEquals('Bambang', $complaint->resident->name);
    }

    /**
     * Skenario 16: Template Excel dan CSV dapat diunduh.
     */
    public function test_template_dapat_diunduh_dan_digunakan(): void
    {
        $this->actingAs($this->admin(), 'web');

        // Template XLSX
        $responseXlsx = $this->get(route('admin.warga.template'));
        $responseXlsx->assertOk();
        $this->assertEquals('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', $responseXlsx->headers->get('content-type'));

        // Template CSV
        $responseCsv = $this->get(route('admin.warga.template', ['format' => 'csv']));
        $responseCsv->assertOk();
        $responseCsv->assertSee('NIK,Nama Lengkap,Alamat,RT,RW,Status');
    }

    /**
     * Skenario 17 & 18: File valid dapat dipreview dan berhasil diimpor.
     */
    public function test_file_valid_dapat_dipreview_dan_berhasil_diimpor(): void
    {
        $admin = $this->admin();
        Storage::fake();

        $csvContent = "NIK,Nama Lengkap,Alamat,RT,RW,Status\n" .
                      "3205010101900010,Dewi Sartika,Jl. Pahlawan No. 1,001,001,Aktif\n" .
                      "3205010101900011,Hasan Basri,Jl. Pahlawan No. 2,001,001,Aktif\n";

        $file = UploadedFile::fake()->createWithContent('data-warga.csv', $csvContent);

        // Preview
        $previewRes = $this->asUser($admin)->postJson('/api/admin/residents/preview-import', [
            'file' => $file,
        ]);

        $previewRes->assertOk();
        $previewRes->assertJson([
            'success' => true,
            'summary' => [
                'total' => 2,
                'valid' => 2,
                'duplicate' => 0,
                'invalid' => 0,
            ]
        ]);

        $token = $previewRes->json('token');
        $this->assertNotNull($token);

        // Confirm Import
        $confirmRes = $this->asUser($admin)->postJson('/api/admin/residents/confirm-import', [
            'token' => $token,
        ]);

        $confirmRes->assertOk();
        $this->assertEquals(2, $confirmRes->json('imported_count'));
        $this->assertDatabaseHas('residents', ['nik' => '3205010101900010', 'name' => 'Dewi Sartika']);
        $this->assertDatabaseHas('residents', ['nik' => '3205010101900011', 'name' => 'Hasan Basri']);
    }

    /**
     * Skenario 19 & 20: Data duplikat terdeteksi dan baris tidak valid menampilkan alasan.
     */
    public function test_data_duplikat_dan_tidak_valid_terdeteksi_dengan_alasan(): void
    {
        $admin = $this->admin();

        // Buat satu warga yang sudah ada di database
        Resident::create([
            'nik' => '3205010101900020',
            'name' => 'Warga Eksis',
            'status' => 'aktif',
            'password' => Hash::make('3205010101900020'),
        ]);

        $csvContent = "NIK,Nama Lengkap,Alamat,RT,RW,Status\n" .
                      "3205010101900020,Warga Duplikat DB,Jl. A,01,02,Aktif\n" . // Duplikat DB
                      "invalid-nik,Nama Warga,Jl. B,01,02,Aktif\n" . // Format NIK tidak valid
                      ",Nama Tanpa NIK,Jl. C,01,02,Aktif\n" . // NIK kosong
                      "3205010101900021,,Jl. D,01,02,Aktif\n"; // Nama kosong

        $file = UploadedFile::fake()->createWithContent('data-campur.csv', $csvContent);

        $previewRes = $this->asUser($admin)->postJson('/api/admin/residents/preview-import', [
            'file' => $file,
        ]);

        $previewRes->assertOk();
        $previewRes->assertJson([
            'summary' => [
                'total' => 4,
                'valid' => 0,
                'duplicate' => 1,
                'invalid' => 3,
            ]
        ]);

        $rows = $previewRes->json('rows');
        $this->assertEquals('duplicate', $rows[0]['status_type']);
        $this->assertStringContainsString('database', $rows[0]['reasons'][0]);

        $this->assertEquals('invalid', $rows[1]['status_type']);
        $this->assertStringContainsString('angka', $rows[1]['reasons'][0]);
    }

    /**
     * Skenario 21: File dengan header salah ditolak.
     */
    public function test_file_dengan_header_salah_ditolak(): void
    {
        $admin = $this->admin();

        $csvContent = "KolomSatu,KolomDua,KolomTiga\n123,Nama,Alamat\n";
        $file = UploadedFile::fake()->createWithContent('header-salah.csv', $csvContent);

        $response = $this->asUser($admin)->postJson('/api/admin/residents/preview-import', [
            'file' => $file,
        ]);

        $response->assertStatus(422);
        $this->assertStringContainsString('header', strtolower($response->json('message')));
    }

    /**
     * Skenario 22: File dengan format tidak sesuai ditolak.
     */
    public function test_file_dengan_format_tidak_sesuai_ditolak(): void
    {
        $admin = $this->admin();

        $file = UploadedFile::fake()->create('dokumen.pdf', 100);

        $response = $this->asUser($admin)->postJson('/api/admin/residents/preview-import', [
            'file' => $file,
        ]);

        $response->assertStatus(422);
    }

    /**
     * Skenario 23 & 24: Import tidak menimpa data warga yang sudah ada.
     */
    public function test_import_tidak_menimpa_data_warga_eksisting(): void
    {
        $admin = $this->admin();

        Resident::create([
            'nik' => '3205010101900030',
            'name' => 'Nama Asli',
            'address' => 'Alamat Asli',
            'status' => 'aktif',
            'password' => Hash::make('passwordAsli123'),
        ]);

        $csvContent = "NIK,Nama Lengkap,Alamat,RT,RW,Status\n" .
                      "3205010101900030,Nama Palsu Ditimpa,Alamat Berubah,01,02,Nonaktif\n";

        $file = UploadedFile::fake()->createWithContent('timpa.csv', $csvContent);

        $preview = $this->asUser($admin)->postJson('/api/admin/residents/preview-import', ['file' => $file]);
        $token = $preview->json('token');

        $confirm = $this->asUser($admin)->postJson('/api/admin/residents/confirm-import', ['token' => $token]);
        $confirm->assertOk();

        // Data warga lama tidak berubah
        $resident = Resident::where('nik', '3205010101900030')->first();
        $this->assertEquals('Nama Asli', $resident->name);
        $this->assertEquals('Alamat Asli', $resident->address);
        $this->assertTrue(Hash::check('passwordAsli123', $resident->password));
    }

    /**
     * Skenario 25: Tracking tiket tetap berfungsi.
     */
    public function test_tracking_tiket_tetap_berfungsi(): void
    {
        $complaint = Complaint::create([
            'ticket_code' => 'LO-261014-ABCD',
            'name' => 'Budi Publik',
            'title' => 'Lampu Mati',
            'description' => 'Di sudut gang',
            'location' => 'Gang Melati',
            'category' => 'Penerangan Jalan',
            'status' => 'process',
        ]);

        $response = $this->get(route('lacak.cari', ['kode' => 'LO-261014-ABCD']));
        $response->assertOk();
        $response->assertSee('LO-261014-ABCD');
        $response->assertSee('Diproses');
    }

    /**
     * Skenario 26: Admin tetap dapat memverifikasi, menolak, dan menyelesaikan pengaduan.
     */
    public function test_admin_tetap_dapat_memproses_pengaduan(): void
    {
        $complaint = Complaint::create([
            'ticket_code' => 'LO-261014-PROC',
            'title' => 'Saluran Mampet',
            'description' => 'Drainase tersumbat',
            'location' => 'RW 05',
            'category' => 'Saluran Air / Drainase',
            'status' => 'submitted',
        ]);

        // Verifikasi & disposisi ke unit
        $this->actingAs($this->admin(), 'web');
        $resVerif = $this->put(route('admin.laporan.status', ['complaint' => $complaint->ticket_code]), [
            'status' => 'verified',
            'unit' => 'Unit Infrastruktur',
        ]);
        $resVerif->assertRedirect();
        $complaint->refresh();
        $this->assertEquals('verified', $complaint->status);
        $this->assertEquals('Unit Infrastruktur', $complaint->unit);

        // Selesaikan pengaduan
        $resSelesai = $this->put(route('admin.laporan.selesaikan', ['complaint' => $complaint->ticket_code]), [
            'catatan' => 'Sudah dikeruk dan lancar kembali',
            'tanggapan' => 'Terima kasih atas laporannya',
        ]);
        $resSelesai->assertRedirect();
        $complaint->refresh();
        $this->assertEquals('completed', $complaint->status);
    }

    /**
     * Skenario 28: Dashboard dan laporan admin tetap berfungsi.
     */
    public function test_dashboard_admin_tetap_berfungsi(): void
    {
        $this->actingAs($this->admin(), 'web');
        $response = $this->get(route('admin.dashboard'));
        $response->assertOk();
        $response->assertSee('Verifikasi');
        $response->assertSee('Disposisi');
        $response->assertSee('Penanganan');
        $response->assertSee('Selesai');
        $response->assertSee('Ditolak');
    }

    /**
     * Skenario 29: Pengguna non-admin tidak dapat mengakses API manajemen Data Warga.
     */
    public function test_pengguna_non_admin_tidak_dapat_mengakses_api_data_warga(): void
    {
        // Tanpa token
        $this->getJson('/api/admin/residents')->assertStatus(401);

        // Akun unit (non-admin)
        $unit = $this->unitAccount();
        $this->asUser($unit)->getJson('/api/admin/residents')->assertStatus(403);
    }
}
