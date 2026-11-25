<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Akun Owner Awal
    |--------------------------------------------------------------------------
    | Dipakai OwnerUserSeeder. Dibaca lewat config agar tetap bekerja saat
    | konfigurasi di-cache (env() langsung tidak terbaca dalam kondisi itu).
    */

    'owner' => [
        'name' => env('OWNER_NAME', 'Owner YOLOO Merch'),
        'email' => env('OWNER_EMAIL'),
        'password' => env('OWNER_PASSWORD'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Akun Admin Contoh
    |--------------------------------------------------------------------------
    | Dipakai AdminUserSeeder, berguna untuk mencoba panel admin dengan role
    | admin. Sama seperti Owner, nilainya dibaca lewat config.
    */

    'admin' => [
        'name' => env('ADMIN_NAME', 'Admin YOLOO Merch'),
        'email' => env('ADMIN_EMAIL'),
        'password' => env('ADMIN_PASSWORD'),
    ],

];
