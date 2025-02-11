<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'User',
            'email' => 'kikiisa89@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt("kikiisaipk4"), // password
            'remember_token' => Str::random(10),
        ]);
    }
}
