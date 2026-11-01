<?php

namespace Tests\Feature;

use App\Models\Complaint;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageViewerAndResultPhotoTest extends TestCase
{
    use RefreshDatabase;

    private Resident $residentA;
    private Resident $residentB;
    private User $admin;
    private User $unitInfra;
    private User $unitAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->residentA = Resident::create([
            'nik' => '3205012345670001',
            'name' => 'Warga A',
            'address' => 'RT 001 RW 001',
            'rt' => '001',
            'rw' => '001',
            'status' => 'aktif',
            'password' => bcrypt('password123'),
            'must_change_password' => false,
        ]);

        $this->residentB = Resident::create([
            'nik' => '3205012345670002',
            'name' => 'Warga B',
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

        $this->unitInfra = User::factory()->create([
            'role' => 'unit',
            'unit' => 'Unit Infrastruktur',
        ]);

        $this->unitAdmin = User::factory()->create([
            'role' => 'unit',
            'unit' => 'Unit Administrasi',
        ]);
    }

    private function createComplaint(array $attributes = []): Complaint
    {
        $file = UploadedFile::fake()->image('bukti.jpg');
        $path = $file->store('complaints', 'public');

        return Complaint::create(array_merge([
            'ticket_code' => 'LO-TEST-' . strtoupper(uniqid()),
            'resident_id' => $this->residentA->id,
            'title' => 'Laporan Kerusakan Jalan',
            'description' => 'Jalan berlubang besar dekat gapura.',
            'location' => 'RT 01 RW 01',
            'category' => 'Infrastruktur Jalan',
            'photo' => $path,
            'status' => 'submitted',
        ], $attributes));
    }

    /**
     * Skenario 1: Foto Bukti Pengaduan dapat dibuka melalui thumbnail dan route foto.
     */
    public function test_01_foto_bukti_tampil_pada_detail_laporan_dan_dapat_diakses(): void
    {
        $complaint = $this->createComplaint();

        $response = $this->actingAs($this->residentA, 'resident')
            ->get(route('lacak.cari', ['kode' => $complaint->ticket_code]));

        $response->assertOk();
        $response->assertSee('Foto Bukti Pengaduan');
        $response->assertSee(route('pengaduan.foto-bukti', $complaint->ticket_code));

        // Akses langsung file foto bukti
        $fileResponse = $this->get(route('pengaduan.foto-bukti', $complaint->ticket_code));
        $fileResponse->assertOk();
    }

    /**
     * Skenario 2: Keterangan "Tidak ada foto bukti pengaduan" muncul jika laporan dibuat tanpa foto.
     */
    public function test_02_keterangan_tidak_ada_foto_bukti_muncul_jika_tanpa_foto(): void
    {
        $complaint = $this->createComplaint(['photo' => null]);

        $response = $this->actingAs($this->residentA, 'resident')
            ->get(route('lacak.cari', ['kode' => $complaint->ticket_code]));

        $response->assertOk();
        $response->assertSee('Tidak ada foto bukti pengaduan');
    }

    /**
     * Skenario 3: Foto Hasil Penanganan muncul pada detail laporan warga pemilik saat status selesai.
     */
    public function test_03_foto_hasil_muncul_pada_detail_laporan_warga_pemilik_saat_selesai(): void
    {
        $hasilFile = UploadedFile::fake()->image('hasil.jpg');
        $hasilPath = $hasilFile->store('complaints', 'public');

        $complaint = $this->createComplaint([
            'status' => 'completed',
            'unit' => 'Unit Infrastruktur',
            'result_photo' => $hasilPath,
            'handling_note' => 'Aspal sudah ditambal rapi.',
            'response_note' => 'Perbaikan jalan telah selesai dilakukan.',
        ]);

        $response = $this->actingAs($this->residentA, 'resident')
            ->get(route('lacak.cari', ['kode' => $complaint->ticket_code]));

        $response->assertOk();
        $response->assertSee('Foto Hasil Penanganan');
        $response->assertSee('Perbaikan jalan telah selesai dilakukan.');
        $response->assertSee(route('pengaduan.foto-hasil', $complaint->ticket_code));
        $response->assertDontSee('Foto hasil penanganan belum tersedia.');

        // Warga pemilik dapat mengakses foto hasil
        $fileResponse = $this->actingAs($this->residentA, 'resident')
            ->get(route('pengaduan.foto-hasil', $complaint->ticket_code));
        $fileResponse->assertOk();
    }

    /**
     * Skenario 4: Foto Hasil TIDAK muncul pada laporan warga lain (warga B melihat laporan warga A).
     */
    public function test_04_foto_hasil_tidak_muncul_pada_laporan_warga_lain_dan_url_dicegah_403(): void
    {
        $hasilFile = UploadedFile::fake()->image('hasil.jpg');
        $hasilPath = $hasilFile->store('complaints', 'public');

        $complaint = $this->createComplaint([
            'status' => 'completed',
            'result_photo' => $hasilPath,
        ]);

        // Warga B melihat tiket milik Warga A
        $response = $this->actingAs($this->residentB, 'resident')
            ->get(route('lacak.cari', ['kode' => $complaint->ticket_code]));

        $response->assertOk();
        $response->assertSee('Foto hasil penanganan belum tersedia.');
        $response->assertDontSee(route('pengaduan.foto-hasil', $complaint->ticket_code));

        // Warga B mencoba memanggil endpoint foto hasil milik Warga A langsung -> DITOLAK 403
        $forbiddenResponse = $this->actingAs($this->residentB, 'resident')
            ->get(route('pengaduan.foto-hasil', $complaint->ticket_code));
        $forbiddenResponse->assertStatus(403);
    }

    /**
     * Skenario 5: Foto Hasil TIDAK ditampilkan sebelum memenuhi aturan publikasi (status belum completed).
     */
    public function test_05_foto_hasil_tidak_ditampilkan_sebelum_memenuhi_aturan_publikasi(): void
    {
        $hasilFile = UploadedFile::fake()->image('draft_hasil.jpg');
        $hasilPath = $hasilFile->store('complaints', 'public');

        // Status masih process (belum selesai/dipublikasikan)
        $complaint = $this->createComplaint([
            'status' => 'process',
            'unit' => 'Unit Infrastruktur',
            'result_photo' => $hasilPath,
        ]);

        $response = $this->actingAs($this->residentA, 'resident')
            ->get(route('lacak.cari', ['kode' => $complaint->ticket_code]));

        $response->assertOk();
        $response->assertSee('Foto hasil penanganan belum tersedia.');
        $response->assertDontSee(route('pengaduan.foto-hasil', $complaint->ticket_code));

        // Request langsung ke file sebelum status selesai ditolak
        $fileResponse = $this->actingAs($this->residentA, 'resident')
            ->get(route('pengaduan.foto-hasil', $complaint->ticket_code));
        $fileResponse->assertStatus(403);
    }

    /**
     * Skenario 6: Pengguna tamu tanpa login tidak dapat melihat foto hasil pengaduan berpemilik warga.
     */
    public function test_06_tamu_tanpa_login_ditolak_melihat_foto_hasil_privat(): void
    {
        $hasilFile = UploadedFile::fake()->image('hasil.jpg');
        $hasilPath = $hasilFile->store('complaints', 'public');

        $complaint = $this->createComplaint([
            'status' => 'completed',
            'result_photo' => $hasilPath,
        ]);

        // Tamu membuka halaman lacak
        $response = $this->get(route('lacak.cari', ['kode' => $complaint->ticket_code]));
        $response->assertOk();
        $response->assertSee('Foto hasil penanganan belum tersedia.');
        $response->assertDontSee(route('pengaduan.foto-hasil', $complaint->ticket_code));

        // Tamu mencoba akses langsung URL foto hasil
        $fileResponse = $this->get(route('pengaduan.foto-hasil', $complaint->ticket_code));
        $fileResponse->assertStatus(403);
    }

    /**
     * Skenario 7: Admin dan Petugas Unit yang berwenang dapat melihat dan mengakses foto hasil.
     */
    public function test_07_admin_dan_unit_berwenang_dapat_melihat_dan_mengakses_foto_hasil(): void
    {
        $hasilFile = UploadedFile::fake()->image('hasil.jpg');
        $hasilPath = $hasilFile->store('complaints', 'public');

        $complaint = $this->createComplaint([
            'status' => 'completed',
            'unit' => 'Unit Infrastruktur',
            'result_photo' => $hasilPath,
        ]);

        // 1. Admin dapat mengakses
        $adminFileRes = $this->actingAs($this->admin, 'web')
            ->get(route('pengaduan.foto-hasil', $complaint->ticket_code));
        $adminFileRes->assertOk();

        // 2. Unit Infrastruktur (sesuai penugasan) dapat mengakses
        $unitFileRes = $this->actingAs($this->unitInfra, 'web')
            ->get(route('pengaduan.foto-hasil', $complaint->ticket_code));
        $unitFileRes->assertOk();
    }

    /**
     * Skenario 8: Petugas Unit lain dilarang mengakses foto hasil unit berbeda.
     */
    public function test_08_petugas_unit_lain_dilarang_mengakses_foto_hasil_unit_berbeda(): void
    {
        $hasilFile = UploadedFile::fake()->image('hasil.jpg');
        $hasilPath = $hasilFile->store('complaints', 'public');

        $complaint = $this->createComplaint([
            'status' => 'completed',
            'unit' => 'Unit Infrastruktur',
            'result_photo' => $hasilPath,
        ]);

        // Unit Administrasi mencoba mengakses pengaduan milik Unit Infrastruktur
        $response = $this->actingAs($this->unitAdmin, 'web')
            ->get(route('pengaduan.foto-hasil', $complaint->ticket_code));
        $response->assertStatus(403);
    }

    /**
     * Skenario 9: Admin dapat mengunggah Foto Hasil ketika menyelesaikan pengaduan (complete).
     */
    public function test_09_admin_dapat_mengunggah_foto_hasil_ketika_menyelesaikan_pengaduan(): void
    {
        $complaint = $this->createComplaint([
            'status' => 'process',
            'unit' => 'Unit Infrastruktur',
        ]);

        $newHasil = UploadedFile::fake()->image('selesai.jpg');

        $response = $this->actingAs($this->admin, 'web')
            ->put(route('admin.laporan.selesaikan', $complaint->ticket_code), [
                'catatan' => 'Pekerjaan selesai 100%',
                'tanggapan' => 'Sudah dapat dilewati warga.',
                'foto_hasil' => $newHasil,
            ]);

        $response->assertStatus(302);
        $complaint->refresh();

        $this->assertEquals('completed', $complaint->status);
        $this->assertNotNull($complaint->result_photo);
        Storage::disk('public')->assertExists($complaint->result_photo);
    }

    /**
     * Skenario 10: Admin dapat mengunggah Foto Hasil ketika menyimpan catatan penanganan.
     */
    public function test_10_admin_dapat_mengunggah_foto_hasil_saat_simpan_catatan_penanganan(): void
    {
        $complaint = $this->createComplaint([
            'status' => 'process',
            'unit' => 'Unit Infrastruktur',
        ]);

        $progressPhoto = UploadedFile::fake()->image('progress.webp');

        $response = $this->actingAs($this->admin, 'web')
            ->put(route('admin.laporan.catatan', $complaint->ticket_code), [
                'catatan' => 'Petugas sedang di lokasi',
                'tanggapan' => 'Sedang dalam pengerjaan',
                'foto_hasil' => $progressPhoto,
            ]);

        $response->assertStatus(302);
        $complaint->refresh();

        $this->assertNotNull($complaint->result_photo);
        Storage::disk('public')->assertExists($complaint->result_photo);
    }

    /**
     * Skenario 11: Admin dapat membatalkan / menghapus foto hasil dengan opsi hapus_foto_hasil.
     */
    public function test_11_admin_dapat_menghapus_foto_hasil_dengan_opsi_hapus(): void
    {
        $hasilFile = UploadedFile::fake()->image('lama.jpg');
        $hasilPath = $hasilFile->store('complaints', 'public');

        $complaint = $this->createComplaint([
            'status' => 'process',
            'unit' => 'Unit Infrastruktur',
            'result_photo' => $hasilPath,
        ]);

        $response = $this->actingAs($this->admin, 'web')
            ->put(route('admin.laporan.catatan', $complaint->ticket_code), [
                'catatan' => 'Hapus foto yang salah',
                'tanggapan' => 'Dibatalkan',
                'hapus_foto_hasil' => '1',
            ]);

        $response->assertStatus(302);
        $complaint->refresh();

        $this->assertNull($complaint->result_photo);
    }

    /**
     * Skenario 12: Backend memvalidasi format dan ukuran file foto hasil (menolak > 5MB & ekstensi ilegal).
     */
    public function test_12_backend_menolak_foto_hasil_di_atas_5mb_dan_format_ilegal(): void
    {
        $complaint = $this->createComplaint([
            'status' => 'process',
            'unit' => 'Unit Infrastruktur',
        ]);

        // 1. File terlalu besar (> 5MB = 6000 KB)
        $largeFile = UploadedFile::fake()->create('besar.jpg', 6000, 'image/jpeg');
        $res1 = $this->actingAs($this->admin, 'web')
            ->put(route('admin.laporan.selesaikan', $complaint->ticket_code), [
                'foto_hasil' => $largeFile,
            ]);
        $res1->assertSessionHasErrors('foto_hasil');

        // 2. Format file tidak diizinkan (.pdf)
        $invalidFile = UploadedFile::fake()->create('dokumen.pdf', 500, 'application/pdf');
        $res2 = $this->actingAs($this->admin, 'web')
            ->put(route('admin.laporan.selesaikan', $complaint->ticket_code), [
                'foto_hasil' => $invalidFile,
            ]);
        $res2->assertSessionHasErrors('foto_hasil');
    }

    /**
     * Skenario 13: API detail menyembunyikan result_photo dari warga lain.
     */
    public function test_13_api_detail_menyembunyikan_result_photo_dari_warga_lain(): void
    {
        $hasilFile = UploadedFile::fake()->image('hasil.jpg');
        $hasilPath = $hasilFile->store('complaints', 'public');

        $complaint = $this->createComplaint([
            'status' => 'completed',
            'result_photo' => $hasilPath,
        ]);

        // Warga B memanggil API show untuk tiket milik Warga A
        $resB = $this->actingAs($this->residentB, 'resident')
            ->getJson("/api/complaints/{$complaint->ticket_code}");

        $resB->assertOk();
        $this->assertNull($resB->json('result_photo'), 'Foto hasil harus disembunyikan (null) untuk warga lain');

        // Warga A memanggil API show untuk tiket miliknya sendiri
        $resA = $this->actingAs($this->residentA, 'resident')
            ->getJson("/api/complaints/{$complaint->ticket_code}");

        $resA->assertOk();
        $this->assertEquals($hasilPath, $resA->json('result_photo'), 'Foto hasil harus tampil untuk warga pemilik');
    }

    /**
     * Skenario 14: Komponen image viewer termuat dengan kontrol lengkap pada halaman lacak dan admin.
     */
    public function test_14_komponen_image_viewer_termuat_pada_halaman_lacak_dan_admin(): void
    {
        $complaint = $this->createComplaint();

        // 1. Pada halaman lacak warga
        $resLacak = $this->actingAs($this->residentA, 'resident')
            ->get(route('lacak.cari', ['kode' => $complaint->ticket_code]));
        $resLacak->assertOk();
        $resLacak->assertSee('x-data="imageViewer()"', false);
        $resLacak->assertSee('zoomIn()', false);
        $resLacak->assertSee('zoomOut()', false);
        $resLacak->assertSee('toggleFullscreen()', false);
        $resLacak->assertSee('openImageViewer', false);

        // 2. Pada halaman dashboard admin
        \Illuminate\Support\Facades\Auth::guard('resident')->logout();
        $resAdmin = $this->actingAs($this->admin, 'web')
            ->get(route('admin.dashboard', ['status' => 'verifikasi', 'kode' => $complaint->ticket_code]));
        $resAdmin->assertOk();
        $resAdmin->assertSee('x-data="imageViewer()"', false);
        $resAdmin->assertSee('openAdminGallery', false);
    }
}
