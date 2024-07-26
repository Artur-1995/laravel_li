<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        User::factory(
            [
                'name' => 'admin',
                'email' => 'admin@mail.ru',
                'email_verified_at' => now(),
                'password' => Hash::make('1111'),
                'remember_token' => Str::random(10),
            ]
        )->create();
        User::factory()->count(5)->create();
    }
}