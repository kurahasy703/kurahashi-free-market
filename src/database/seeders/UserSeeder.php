<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            [
                'email' => 'test@example.com',
            ],

            [
                'name' => 'テスト太郎',
                'password' => Hash::make('password123'),
                'profile_image' => 'profiles/test_user1.png',
                'postal_code' => '123-4567',
                'address' => '東京都渋谷区道玄坂x-x-x',
                'building_name' => 'コーチテックビル',
            ]
        );

        User::updateOrCreate(
            [
                'email' => 'test2@example.com',
            ],
            [
                'name' => 'テスト次郎',
                'password' => Hash::make('password123'),
                'profile_image' => 'profiles/test_user2.png',
                'postal_code' => '123-4567',
                'address' => '東京都渋谷区道玄坂xx-x-x',
                'building_name' => 'コーチテックビル',
            ]
        );
    }
}
