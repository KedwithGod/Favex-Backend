<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'demo@example.com'],
            [
                'first_name' => 'Demo',
                'last_name'  => 'User',
                'username'   => 'demouser',
                'phone'      => '08000000000',
                'password'   => Hash::make('Password123!GG'),
                'txn_pin_hash' => Hash::make('1234'),
                'where_heard'  => 'Friend',
                'referral_tag' => 'REF123',
            ]
        );
    }
}
