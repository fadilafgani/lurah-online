<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_lupa_kata_sandi_dapat_dibuka(): void
    {
        $this->get(route('password.request'))->assertOk();
        $this->get(route('admin.forgot-password'))->assertOk();
    }

    public function test_email_reset_terkirim_untuk_email_terdaftar(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post(route('password.email'), ['email' => $user->email])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.check-email'));

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_email_tidak_terdaftar_menampilkan_pesan_bahasa_indonesia(): void
    {
        $this->post(route('password.email'), ['email' => 'tidak-ada@example.test'])
            ->assertSessionHasErrors('email');

        $error = session('errors')->first('email');

        // Tanpa lang/id/passwords.php, __($status) mengembalikan key mentah.
        $this->assertStringNotContainsString('passwords.', $error);
        $this->assertSame(__('passwords.user'), $error);
    }

    public function test_link_reset_dari_email_dapat_dibuka(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $this->post(route('password.email'), ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $this->get(route('password.reset', [
                'token' => $notification->token,
                'email' => $user->email,
            ]))->assertOk()->assertSee('Atur Ulang Kata Sandi', false);

            return true;
        });
    }

    public function test_kata_sandi_berhasil_diubah_dengan_token_valid(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $this->post(route('password.email'), ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $this->post(route('password.update'), [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'kata-sandi-baru',
                'password_confirmation' => 'kata-sandi-baru',
            ])
                ->assertSessionHasNoErrors()
                ->assertRedirect(route('admin.login'));

            $this->assertTrue(Hash::check('kata-sandi-baru', $user->fresh()->password));

            return true;
        });
    }

    public function test_token_tidak_valid_ditolak_dengan_pesan_bahasa_indonesia(): void
    {
        $user = User::factory()->create();

        $this->post(route('password.update'), [
            'token' => 'token-palsu',
            'email' => $user->email,
            'password' => 'kata-sandi-baru',
            'password_confirmation' => 'kata-sandi-baru',
        ])->assertSessionHasErrors('email');

        $this->assertSame(__('passwords.token'), session('errors')->first('email'));
    }

    public function test_konfirmasi_kata_sandi_harus_cocok(): void
    {
        $user = User::factory()->create();

        $this->post(route('password.update'), [
            'token' => 'apa-saja',
            'email' => $user->email,
            'password' => 'kata-sandi-baru',
            'password_confirmation' => 'beda-sekali',
        ])->assertSessionHasErrors('password');
    }

    public function test_permintaan_berulang_dibatasi_dan_pesannya_tampil_di_halaman_periksa_email(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post(route('password.email'), ['email' => $user->email])
            ->assertSessionHasNoErrors();

        // Throttle broker 60 detik: permintaan kedua ditolak.
        $this->from(route('admin.check-email'))
            ->post(route('password.email'), ['email' => $user->email])
            ->assertRedirect(route('admin.check-email'))
            ->assertSessionHasErrors('email');

        $this->assertSame(__('passwords.throttled'), session('errors')->first('email'));

        // Halaman periksa-email harus menampilkan pesan itu, bukan menelannya.
        $this->followingRedirects()
            ->from(route('admin.check-email'))
            ->post(route('password.email'), ['email' => $user->email])
            ->assertOk()
            ->assertSee(__('passwords.throttled'), false);
    }

    public function test_email_reset_memakai_bahasa_indonesia(): void
    {
        $user = User::factory()->create();
        $mail = (new ResetPassword('token-contoh'))->toMail($user);

        $this->assertStringContainsString('Atur Ulang Kata Sandi', $mail->subject);
        $this->assertSame('Atur Ulang Kata Sandi', $mail->actionText);
        $this->assertStringContainsString('/reset-password/token-contoh', $mail->actionUrl);
    }
}
