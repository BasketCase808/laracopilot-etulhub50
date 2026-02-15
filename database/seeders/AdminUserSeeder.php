<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@bitcoinrpc.local',
            'is_admin' => true,
            'password' => Hash::make('qwerty123'),
            'email_verified_at' => now()
        ]);
    }
}