<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Laravolt\Indonesia\Seeds\CitiesSeeder;
use Laravolt\Indonesia\Seeds\ProvincesSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\Product::factory(10)->create();
        // \App\Models\Supplier::factory(10)->create();
        // \App\Models\User::factory(10)->create();

        Admin::create([
            'name' => 'Test Admin',
            'username' => 'test',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);

        $this->call(
            [

                ProvincesSeeder::class,
                CitiesSeeder::class,
            ]
        );

        // \App\Models\ShippingCost::factory(10)->create();
    }
}
