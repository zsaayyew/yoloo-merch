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

];
