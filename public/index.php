<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

// Auto migrate darurat untuk Render Free Plan agar tabel database otomatis terbuat
try {
    $app->make(\Illuminate\Contracts\Console\Kernel::class);
    if (!Schema::hasTable('migrations')) {
        Artisan::call('migrate', ['--force' => true]);
    }
} catch (\Exception $e) {
    // Abaikan jika database sedang sibuk/belum siap
}

$app->handleRequest(Request::capture());