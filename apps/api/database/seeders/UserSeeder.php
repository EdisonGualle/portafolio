<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'ed.gualle@gmail.com'],
            [
                'name' => 'Edison Gualle',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'has_email_authentication' => false,
            ]
        );
    }
}
