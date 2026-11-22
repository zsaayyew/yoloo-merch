<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// PRD-03 ORD: bersihkan keranjang, token, dan file sementara yang kedaluwarsa (ORD-BR-010)
\Illuminate\Support\Facades\Schedule::command('ordering:purge-expired')->hourly();