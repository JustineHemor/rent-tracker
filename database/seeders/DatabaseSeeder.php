<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Justine Hemor',
            'email' => 'hemor.justine14@gmail.com',
            'password' => Hash::make('password'),
            'rent_amount' => 3600,
        ]);

        User::factory()->create([
            'name' => 'Janna Cruto',
            'email' => 'janna.cruto@gmail.com',
            'password' => Hash::make('password'),
            'rent_amount' => 3600,
        ]);

        User::factory()->create([
            'name' => 'Jane Jaso',
            'email' => 'jane.jaso@gmail.com',
            'password' => Hash::make('password'),
            'rent_amount' => 4800,
        ]);
    }
}
