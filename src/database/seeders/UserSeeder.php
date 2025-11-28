<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 管理者
        User::create([
            'name' => 'TestAdmin',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('adminpass'),
            'role' => 2,
        ]);

        // 店舗代表者（20人）
        for ($i = 1; $i <= 20; $i++) {
            User::create([
                'name' => "TestOwner{$i}",
                'email' => "owner{$i}@example.com",
                'email_verified_at' => now(),
                'password' => Hash::make('ownerpass'),
                'role' => 1,
            ]);
        }

        // ユーザー
        User::create([
            'name' => 'TestUser',
            'email' => 'user@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 0,
        ]);
    }
}
