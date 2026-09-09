<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Pesan Validasi
    |--------------------------------------------------------------------------
    |
    | Hanya aturan yang dipakai form autentikasi. Tambahkan sesuai kebutuhan.
    |
    */

    'confirmed' => 'Konfirmasi :attribute tidak cocok.',
    'email' => 'Format :attribute tidak valid.',
    'min' => [
        'string' => ':attribute minimal :min karakter.',
    ],
    'required' => ':attribute wajib diisi.',

    'attributes' => [
        'email' => 'Email',
        'password' => 'Kata sandi',
        'password_confirmation' => 'Ulangi kata sandi',
        'token' => 'Token',
    ],

];
