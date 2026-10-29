<?php

namespace Tests\Feature;

use App\Models\Complaint;
use App\Models\ProhibitedWord;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProhibitedWordsTest extends TestCase
{
    use RefreshDatabase;

    private Resident $resident;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->resident = Resident::create([
            'nik' => '3205012345670001',
            'name' => 'Warga Pertama',
            'address' => 'RT 001 RW 001',
            'rt' => '001',
            'rw' => '001',
            'status' => 'aktif',
            'password' => bcrypt('password123'),
            'must_change_password' => false,
        ]);
    }

    public function test_pengaduan_warga_ditolak_jika_mengandung_kata_terlarang(): void
    {
        $response = $this->actingAs($this->resident, 'resident')
            ->from(route('pengaduan.buat'))
            ->followingRedirects()
            ->post(route('pengaduan.simpan'), [
                'judul' => 'Jalan 4nj1ng rusak',
                'kategori' => 'Infrastruktur Jalan',
                'deskripsi' => 'Aspal berlubang di depan rumah.',
                'lokasi' => 'RT 01 RW 01',
                'foto' => UploadedFile::fake()->image('bukti.jpg'),
                'anonim' => false,
            ]);

        $response->assertOk()
            ->assertSee('Mohon periksa kembali laporan Anda')
            ->assertSee('Laporan belum dapat dikirim. Silakan perbaiki kata-kata dalam laporan agar lebih sopan, lalu coba kirim kembali.');
        $this->assertSame(0, Complaint::count());
    }

    public function test_kata_terlarang_tidak_mencocok_bagian_dari_kata_lain(): void
    {
        $response = $this->actingAs($this->resident, 'resident')->post(route('pengaduan.simpan'), [
            'judul' => 'Lantai jalan rusak',
            'kategori' => 'Infrastruktur Jalan',
            'deskripsi' => 'Aspal berlubang di depan rumah.',
            'lokasi' => 'RT 01 RW 01',
            'foto' => UploadedFile::fake()->image('bukti.jpg'),
            'anonim' => false,
        ]);

        $response->assertRedirect(route('pengaduan.sukses', [
            'kode' => Complaint::firstOrFail()->ticket_code,
        ]));
        $this->assertSame(1, Complaint::count());
    }

    public function test_kata_yang_disimpan_admin_dipakai_di_form_dan_api_pengaduan(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin, 'web')
            ->post(route('admin.kata-terlarang.update'), ['words' => ['bahaya']])
            ->assertRedirect(route('admin.kata-terlarang'));

        $this->assertDatabaseHas('prohibited_words', ['word' => 'bahaya']);
        $this->actingAs($admin, 'web')
            ->get(route('admin.kata-terlarang'))
            ->assertOk()
            ->assertSee('bahaya');

        $webResponse = $this->actingAs($this->resident, 'resident')->post(route('pengaduan.simpan'), [
            'judul' => 'Ada bahaya di jalan',
            'kategori' => 'Infrastruktur Jalan',
            'deskripsi' => 'Aspal berlubang di depan rumah.',
            'lokasi' => 'RT 01 RW 01',
            'foto' => UploadedFile::fake()->image('bukti.jpg'),
            'anonim' => false,
        ]);

        $webResponse->assertSessionHasErrors('judul');
        $this->assertSame(0, Complaint::count());

        $this->postJson('/api/complaints', [
            'title' => 'Ada bahaya di jalan',
            'description' => 'Aspal berlubang di depan rumah.',
            'location' => 'RT 01 RW 01',
            'category' => 'Infrastruktur Jalan',
            'phone' => '08123456789',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors('title')
            ->assertJsonPath('errors.title.0', 'Laporan belum dapat dikirim. Silakan perbaiki kata-kata dalam laporan agar lebih sopan, lalu coba kirim kembali.');

        $this->assertSame(0, Complaint::count());
        $this->assertSame(1, ProhibitedWord::count());
    }
}
