<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Recipe;
use Illuminate\Support\Facades\Hash;

class UserRecipeSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario Nuria
        $nuria = User::firstOrCreate(
            ['email' => 'nuria@test.com'],
            [
                'name' => 'nuria',
                'password' => Hash::make('password'),
            ]
        );

        Recipe::factory()
            ->count(5)
            ->for($nuria)
            ->create();

        // Usuario Pablo
        $pablo = User::firstOrCreate(
            ['email' => 'pablo@test.com'],
            [
                'name' => 'pablo',
                'password' => Hash::make('password'),
            ]
        );

        Recipe::factory()
            ->count(5)
            ->for($pablo)
            ->create();
    }
}
