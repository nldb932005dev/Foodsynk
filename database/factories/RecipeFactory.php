<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RecipeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'titulo' => $this->faker->sentence(3),
            'foto' => 'recipes/default.jpg',
            'pasos' => $this->faker->paragraphs(3, true),
        ];
    }
}