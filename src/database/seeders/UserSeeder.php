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
            // 既存ユーザーを探す条件
            [
                'email' => 'test@example.com',
            ],
            // 登録・更新する内容
            [
                'name' => 'テスト太郎',
                'password' => Hash::make('password123'),
                'profile_image' => 'profiles/test_user.jpg',
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
                'profile_image' => 'profiles/test_user.jpg',
                'postal_code' => '123-4567',
                'address' => '東京都渋谷区道玄坂xx-x-x',
                'building_name' => 'コーチテックビル',
            ]
        );
    }
}
