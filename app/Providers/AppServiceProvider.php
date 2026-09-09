<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        $this->configureResetPasswordMail();
    }

    /**
     * Email reset kata sandi dalam bahasa Indonesia.
     *
     * Notifikasi bawaan Laravel berbahasa Inggris dan hanya bisa diterjemahkan
     * lewat file lang JSON. Menyusunnya di sini lebih jelas dan sekaligus
     * memakai nama aplikasi serta masa berlaku token dari config.
     */
    protected function configureResetPasswordMail(): void
    {
        ResetPassword::toMailUsing(function (object $notifiable, string $token): MailMessage {
            $url = URL::route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ]);

            $expire = config('auth.passwords.'.config('auth.defaults.passwords').'.expire');

            return (new MailMessage)
                ->subject('Atur Ulang Kata Sandi - '.config('app.name'))
                ->greeting('Halo!')
                ->line('Kami menerima permintaan untuk mengatur ulang kata sandi akun Anda.')
                ->action('Atur Ulang Kata Sandi', $url)
                ->line("Link ini hanya berlaku {$expire} menit.")
                ->line('Jika Anda tidak meminta pengaturan ulang kata sandi, abaikan email ini — kata sandi Anda tidak akan berubah.')
                ->salutation('Terima kasih, '.config('app.name'));
        });
    }
}
