<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Reset password for admin
DB::table('users')
    ->where('email', 'admin@gmail.com')
    ->update(['password' => Hash::make('admin123')]);

echo "Password untuk admin@gmail.com telah direset ke: admin123\n";

// Reset password for cashier
DB::table('users')
    ->where('email', 'cashier@foodstall.com')
    ->update(['password' => Hash::make('kasir123')]);

echo "Password untuk cashier@foodstall.com telah direset ke: kasir123\n";

// Also reset kasir1
DB::table('users')
    ->where('email', 'kasir1@foodstall.com')
    ->update(['password' => Hash::make('kasir123')]);

echo "Password untuk kasir1@foodstall.com telah direset ke: kasir123\n";

echo "\n=== CREDENTIALS ===\n";
echo "Admin: admin@gmail.com / admin123\n";
echo "Kasir: cashier@foodstall.com / kasir123\n";
echo "Kasir: kasir1@foodstall.com / kasir123\n";
