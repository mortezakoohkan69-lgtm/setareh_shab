<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'مدیر سیستم',
                'email' => 'admin@test.com',
                'mobile' => '09123456789',
                'password' => Hash::make('12345678'),
                'is_active' => true,
            ],
        );
    }
}
