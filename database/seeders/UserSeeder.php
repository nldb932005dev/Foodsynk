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

        $admin = User::firstOrCreate(
            ['email' => 'admin@foodsynk.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('Admin1234!'),
            ]
        );

        if (! $admin->is_admin) {
            $admin->is_admin = true;
            $admin->save();
        }
    }
}
