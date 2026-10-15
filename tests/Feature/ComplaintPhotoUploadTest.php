<?php

namespace Tests\Feature;

use App\Models\Complaint;
use App\Models\Resident;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ComplaintPhotoUploadTest extends TestCase
{
    use RefreshDatabase;

    private Resident $resident;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resident = Resident::create([
            'nik' => '3205012345670001',
            'name' => 'Budi Santoso',
            'address' => 'Jl. Mawar No. 10',
            'rt' => '001',
            'rw' => '002',
            'status' => 'aktif',
            'password' => bcrypt('password123'),
            'must_change_password' => false,
        ]);

        Storage::fake('public');
    }

    public function test_halaman_pengaduan_memiliki_opsi_kamera_dan_galeri(): void
    {
        $response = $this->actingAs($this->resident, 'resident')->get(route('pengaduan.buat'));

        $response->assertStatus(200);
        $response->assertSee('Ambil Foto dari Kamera');
        $response->assertSee('Pilih dari Galeri');
        $response->assertSee('capture="environment"', false);
        $response->assertSee('id="camera-input"', false);
        $response->assertSee('id="gallery-input"', false);
        $response->assertSee('id="foto-input"', false);
        $response->assertSee('Hapus Foto');
        $response->assertSee('Ganti foto:');
    }

    public function test_warga_berhasil_mengirim_pengaduan_dengan_foto_jpg(): void
    {
        $file = UploadedFile::fake()->image('bukti-lapangan.jpg', 800, 600)->size(1500);

        $response = $this->actingAs($this->resident, 'resident')->post(route('pengaduan.simpan'), [
            'judul' => 'Jalan Rusak Parah di Gang Melati',
            'kategori' => 'Infrastruktur Jalan',
            'deskripsi' => 'Aspal berlubang besar dan membahayakan pengendara motor.',
            'lokasi' => 'RT 01 RW 02 Depan Musholla',
            'foto' => $file,
            'anonim' => false,
        ]);

        $complaint = Complaint::where('title', 'Jalan Rusak Parah di Gang Melati')->first();
        $this->assertNotNull($complaint);
        $this->assertEquals($this->resident->id, $complaint->resident_id);

        $response->assertRedirect(route('pengaduan.sukses', ['kode' => $complaint->ticket_code]));
        Storage::disk('public')->assertExists($complaint->photo);
    }

    public function test_warga_berhasil_mengirim_pengaduan_dengan_foto_png(): void
    {
        $file = UploadedFile::fake()->image('bukti-sampah.png', 800, 600)->size(2000);

        $response = $this->actingAs($this->resident, 'resident')->post(route('pengaduan.simpan'), [
            'judul' => 'Tumpukan Sampah Liar',
            'kategori' => 'Kebersihan & Sampah',
            'deskripsi' => 'Sampah menumpuk di pinggir sungai belum diangkut.',
            'lokasi' => 'RT 02 RW 02 Dekat Jembatan',
            'foto' => $file,
            'anonim' => true,
        ]);

        $complaint = Complaint::where('title', 'Tumpukan Sampah Liar')->first();
        $this->assertNotNull($complaint);
        $this->assertTrue($complaint->is_anonymous);
        $response->assertRedirect(route('pengaduan.sukses', ['kode' => $complaint->ticket_code]));
        Storage::disk('public')->assertExists($complaint->photo);
    }

    public function test_warga_berhasil_mengirim_pengaduan_dengan_foto_webp(): void
    {
        $file = UploadedFile::fake()->image('bukti-lampu.webp', 800, 600)->size(1000);

        $response = $this->actingAs($this->resident, 'resident')->post(route('pengaduan.simpan'), [
            'judul' => 'Lampu PJU Padam',
            'kategori' => 'Penerangan Jalan',
            'deskripsi' => 'Lampu penerangan mati sudah 4 malam.',
            'lokasi' => 'RT 01 RW 02 Gang Kenanga',
            'foto' => $file,
            'anonim' => false,
        ]);

        $complaint = Complaint::where('title', 'Lampu PJU Padam')->first();
        $this->assertNotNull($complaint);
        $response->assertRedirect(route('pengaduan.sukses', ['kode' => $complaint->ticket_code]));
        Storage::disk('public')->assertExists($complaint->photo);
    }

    public function test_unggah_gagal_jika_foto_tidak_disertakan(): void
    {
        $response = $this->actingAs($this->resident, 'resident')->post(route('pengaduan.simpan'), [
            'judul' => 'Saluran Air Mampet',
            'kategori' => 'Saluran Air / Drainase',
            'deskripsi' => 'Drainase tersumbat saat hujan lebat.',
            'lokasi' => 'RT 01 RW 02',
            // Tanpa foto
        ]);

        $response->assertSessionHasErrors('foto');
        $this->assertDatabaseMissing('complaints', [
            'title' => 'Saluran Air Mampet',
        ]);
    }

    public function test_unggah_gagal_jika_ukuran_foto_melebihi_5mb(): void
    {
        // 6000 KB > 5120 KB (5MB)
        $file = UploadedFile::fake()->create('foto-raksasa.jpg', 6000, 'image/jpeg');

        $response = $this->actingAs($this->resident, 'resident')->post(route('pengaduan.simpan'), [
            'judul' => 'Pohon Tumbang',
            'kategori' => 'Keamanan & Ketertiban',
            'deskripsi' => 'Dahan pohon menutupi badan jalan.',
            'lokasi' => 'Jl. Utama RT 01',
            'foto' => $file,
        ]);

        $response->assertSessionHasErrors(['foto' => 'Ukuran foto maksimal adalah 5MB.']);
        $this->assertDatabaseMissing('complaints', [
            'title' => 'Pohon Tumbang',
        ]);
    }

    public function test_unggah_gagal_jika_format_file_bukan_gambar(): void
    {
        $file = UploadedFile::fake()->create('dokumen.pdf', 500, 'application/pdf');

        $response = $this->actingAs($this->resident, 'resident')->post(route('pengaduan.simpan'), [
            'judul' => 'Surat Pengantar',
            'kategori' => 'Administrasi Kependudukan',
            'deskripsi' => 'Mohon cek berkas pengantar.',
            'lokasi' => 'Kantor Desa',
            'foto' => $file,
        ]);

        $response->assertSessionHasErrors('foto');
        $this->assertDatabaseMissing('complaints', [
            'title' => 'Surat Pengantar',
        ]);
    }
}
