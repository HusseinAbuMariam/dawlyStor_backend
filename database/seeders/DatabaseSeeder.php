<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Platform;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Platform::firstOrCreate(['id' => 1], ['name' => 'Platform']);
        $this->call([
            PermissionsSeeder::class,
            SuperAdminsSeeder::class,
                // CustomerSeeder::class,
            StoreSeeder::class,
            // StoresOverTimeSeeder::class,
            // OrderSeeder::class,
            // BetaMarketExtraSeeder::class,
            // TransactionSeeder::class,
            // PlatformCommissionSeeder::class,
            ProductImagesSeeder::class,
        ]);
    }
}
