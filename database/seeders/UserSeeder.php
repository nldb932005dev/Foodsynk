<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'nuria@test.com'],
            [
                'name' => 'nuria',
                'password' => Hash::make('password'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'pablo@test.com'],
            [
                'name' => 'pablo',
                'password' => Hash::make('password'),
            ]
        );
    }
}
