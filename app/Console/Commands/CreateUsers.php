<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateUsers extends Command
{
    protected $signature = 'app:create-users';

    protected $description = 'Create users';

    public function handle(): void
    {
        User::query()->updateOrCreate([
            'email' => 'hemor.justine14@gmail.com',
        ],[
            'name' => 'Justine Hemor',
            'password' => Hash::make('password'),
            'rent_amount' => 3600,
        ]);

        User::query()->updateOrCreate([
            'email' => 'janna.cruto@gmail.com',
        ],[
            'name' => 'Janna Cruto',
            'password' => Hash::make('password'),
            'rent_amount' => 3600,
        ]);

        User::query()->updateOrCreate([
            'email' => 'jane.jaso@gmail.com',
        ],[
            'name' => 'Jane Jaso',
            'password' => Hash::make('password'),
            'rent_amount' => 4800,
        ]);
    }
}
