<?php

namespace Database\Seeders;

use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@symatechlabs.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('Admin@123456'),
                'role' => 'admin',
                'status' => UserStatus::Active,
            ]
        );
    }
}
