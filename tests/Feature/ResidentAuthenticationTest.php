<?php

namespace Tests\Feature;

use App\Models\Complaint;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ResidentAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function createActiveResident(array $attributes = []): Resident
    {
        $nik = $attributes['nik'] ?? '3205012345670001';
        return Resident::create(array_merge([
            'nik' => $nik,
            'name' => 'Budi Santoso',
            'address' => 'Jl. Mawar No. 10',
            'rt' => '001',
            'rw' => '002',
            'status' => 'aktif',
            'password' => Hash::make($nik),
            'must_change_password' => true,
        ], $attributes));
    }

    /**
     * Skenario 1: Warga terdaftar berhasil login menggunakan NIK dan password awal.
     */
    public function test_warga_terdaftar_berhasil_login_menggunakan_nik_dan_password_awal(): void
    {
        $resident = $this->createActiveResident();

        $response = $this->post(route('warga.login.submit'), [
            'nik' => $resident->nik,
            'password' => $resident->nik, // Password awal adalah NIK
        ]);

        $this->assertAuthenticatedAs($resident, 'resident');
        // Karena masih memakai password awal, warga diarahkan ke halaman ubah sandi
        $response->assertRedirect(route('warga.ganti-password'));
    }

    /**
     * Skenario 2: Warga dapat mengganti password awal.
     */
    public function test_warga_dapat_mengganti_password_awal(): void
    {
        $resident = $this->createActiveResident();

        $response = $this->actingAs($resident, 'resident')
            ->post(route('warga.ganti-password.submit'), [
                'current_password' => $resident->nik,
                'password' => 'passwordBaru123',
                'password_confirmation' => 'passwordBaru123',
            ]);

        $response->assertRedirect(route('pengaduan.buat'));
        $resident->refresh();

        $this->assertTrue(Hash::check('passwordBaru123', $resident->password));
        $this->assertFalse($resident->must_change_password);
    }

    /**
     * Skenario 3: Password baru dapat digunakan untuk login berikutnya.
     */
    public function test_password_baru_dapat_digunakan_untuk_login_berikutnya(): void
    {
        $resident = $this->createActiveResident([
            'password' => Hash::make('passwordBaru123'),
            'must_change_password' => false,
        ]);

        $response = $this->post(route('warga.login.submit'), [
            'nik' => $resident->nik,
            'password' => 'passwordBaru123',
        ]);

        $this->assertAuthenticatedAs($resident, 'resident');
        $response->assertRedirect(route('pengaduan.buat'));
    }

    /**
     * Skenario 4: NIK tidak terdaftar tidak dapat login.
     */
    public function test_nik_tidak_terdaftar_tidak_dapat_login(): void
    {
        $response = $this->from(route('warga.login'))->post(route('warga.login.submit'), [
            'nik' => '9999999999999999',
            'password' => 'apapun',
        ]);

        $this->assertGuest('resident');
        $response->assertRedirect(route('warga.login'));
        $response->assertSessionHasErrors('nik');
    }

    /**
     * Skenario 5: Password salah ditolak.
     */
    public function test_password_salah_ditolak(): void
    {
        $resident = $this->createActiveResident();

        $response = $this->from(route('warga.login'))->post(route('warga.login.submit'), [
            'nik' => $resident->nik,
            'password' => 'salah123',
        ]);

        $this->assertGuest('resident');
        $response->assertRedirect(route('warga.login'));
        $response->assertSessionHasErrors('password');
    }

    /**
     * Skenario 6: Warga nonaktif tidak dapat login.
     */
    public function test_warga_nonaktif_tidak_dapat_login(): void
    {
        $resident = $this->createActiveResident([
            'status' => 'nonaktif',
        ]);

        $response = $this->from(route('warga.login'))->post(route('warga.login.submit'), [
            'nik' => $resident->nik,
            'password' => $resident->nik,
        ]);

        $this->assertGuest('resident');
        $response->assertRedirect(route('warga.login'));
        $response->assertSessionHasErrors('nik');
    }

    /**
     * Skenario 7: Warga yang belum login tidak dapat submit pengaduan.
     */
    public function test_warga_belum_login_tidak_dapat_submit_pengaduan(): void
    {
        Storage::fake('public');

        $response = $this->post(route('pengaduan.simpan'), [
            'judul' => 'Jalan Rusak',
            'kategori' => 'Infrastruktur Jalan',
            'deskripsi' => 'Ada lubang besar di tengah jalan',
            'lokasi' => 'Jl. Melati RT 01',
            'foto' => UploadedFile::fake()->image('bukti.jpg'),
        ]);

        // Belum login diarahkan ke login warga
        $response->assertRedirect(route('warga.login'));
        $this->assertEquals(0, Complaint::count());
    }

    /**
     * Skenario 8: Warga yang sudah login diarahkan ke form pengaduan.
     */
    public function test_warga_yang_sudah_login_diarahkan_ke_form_pengaduan(): void
    {
        $resident = $this->createActiveResident([
            'must_change_password' => false,
        ]);

        $response = $this->actingAs($resident, 'resident')->get(route('pengaduan.buat'));
        $response->assertOk();
        $response->assertSee($resident->name);
        $response->assertSee($resident->nik);
    }

    /**
     * Skenario 9: Identitas pengaduan berasal dari akun yang terautentikasi.
     */
    public function test_identitas_pengaduan_berasal_dari_akun_terautentikasi(): void
    {
        Storage::fake('public');

        $resident = $this->createActiveResident([
            'name' => 'Warga Asli Desa',
            'nik' => '3205011122334455',
            'must_change_password' => false,
        ]);

        $response = $this->actingAs($resident, 'resident')->post(route('pengaduan.simpan'), [
            'judul' => 'Sampah Menumpuk',
            'kategori' => 'Kebersihan & Sampah',
            'deskripsi' => 'Sampah tidak diangkut selama tiga hari',
            'lokasi' => 'Jl. Kenanga No. 5',
            'foto' => UploadedFile::fake()->image('sampah.jpg'),
            'anonim' => 0,
        ]);

        $this->assertEquals(1, Complaint::count());
        $complaint = Complaint::first();

        $this->assertEquals($resident->id, $complaint->resident_id);
        $this->assertEquals($resident->name, $complaint->name);
        $response->assertRedirect(route('pengaduan.sukses', ['kode' => $complaint->ticket_code]));
    }

    /**
     * Skenario 10: Warga tidak dapat membuat pengaduan atas nama warga lain dengan memodifikasi request.
     */
    public function test_warga_tidak_dapat_membuat_pengaduan_atas_nama_warga_lain(): void
    {
        Storage::fake('public');

        $residentA = $this->createActiveResident(['nik' => '3205011111111111', 'name' => 'Warga A', 'must_change_password' => false]);
        $residentB = $this->createActiveResident(['nik' => '3205012222222222', 'name' => 'Warga B', 'must_change_password' => false]);

        // Warga A mencoba inject resident_id dan name milik Warga B di payload
        $response = $this->actingAs($residentA, 'resident')->post(route('pengaduan.simpan'), [
            'resident_id' => $residentB->id,
            'name' => 'Warga B',
            'judul' => 'Lampu Jalan Mati',
            'kategori' => 'Penerangan Jalan',
            'deskripsi' => 'Lampu PJU mati di perempatan',
            'lokasi' => 'RT 02 RW 01',
            'foto' => UploadedFile::fake()->image('lampu.jpg'),
        ]);

        $complaint = Complaint::first();
        // Server tetap mengikat pengaduan ke residentA yang terautentikasi
        $this->assertEquals($residentA->id, $complaint->resident_id);
        $this->assertEquals($residentA->name, $complaint->name);
    }

    /**
     * Skenario 27: Fitur anonim tetap menyembunyikan identitas dari tampilan publik,
     * tetapi admin berwenang tetap dapat melihat identitasnya.
     */
    public function test_fitur_anonim_menyembunyikan_identitas_publik_namun_tampak_di_admin(): void
    {
        Storage::fake('public');

        $resident = $this->createActiveResident(['name' => 'Siti Aisyah', 'must_change_password' => false]);

        $this->actingAs($resident, 'resident')->post(route('pengaduan.simpan'), [
            'judul' => 'Pengaduan Rahasia',
            'kategori' => 'Keamanan & Ketertiban',
            'deskripsi' => 'Ada aktivitas mencurigakan',
            'lokasi' => 'Jl. Melati No. 8',
            'foto' => UploadedFile::fake()->image('rahasia.jpg'),
            'anonim' => 1,
        ]);

        $complaint = Complaint::first();
        $this->assertTrue($complaint->is_anonymous);

        // Tampilan publik (Lacak Tiket) menampilkan "Anonim"
        $this->assertEquals('Anonim', $complaint->public_reporter_name);
        $lacakResponse = $this->get(route('lacak.cari', ['kode' => $complaint->ticket_code]));
        $lacakResponse->assertOk()->assertSee('Anonim');

        // Tampilan Admin (Dashboard Controller) menampilkan identitas asli beserta tanda anonim
        $this->assertStringContainsString('Siti Aisyah', $complaint->admin_reporter_name);
        $this->assertStringContainsString('Anonim di Publik', $complaint->admin_reporter_name);
    }
}
