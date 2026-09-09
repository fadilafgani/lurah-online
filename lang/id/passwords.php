<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Pesan Reset Kata Sandi
    |--------------------------------------------------------------------------
    |
    | Baris berikut adalah pesan default yang dikembalikan password broker
    | saat mencoba mengatur ulang kata sandi. Tanpa file ini, __($status) di
    | controller menampilkan key mentah seperti "passwords.user" ke pengguna.
    |
    */

    'reset' => 'Kata sandi Anda berhasil diubah.',
    'sent' => 'Kami telah mengirimkan link atur ulang kata sandi ke email Anda.',
    'throttled' => 'Mohon tunggu sebentar sebelum mencoba lagi.',
    'token' => 'Link atur ulang kata sandi ini tidak valid atau sudah kedaluwarsa. Silakan minta link baru.',
    'user' => 'Kami tidak dapat menemukan pengguna dengan alamat email tersebut.',

];
