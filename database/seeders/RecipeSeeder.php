<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RecipeSeeder extends Seeder
{
    public function run(): void
    {
        $usersByEmail = User::query()
            ->whereIn('email', ['nuria@test.com', 'pablo@test.com'])
            ->pluck('id', 'email')
            ->toArray();

        if (!isset($usersByEmail['nuria@test.com']) || !isset($usersByEmail['pablo@test.com'])) {
            return;
        }

        Recipe::query()
            ->whereIn('user_id', array_values($usersByEmail))
            ->delete();

        $recipes = [
            [
                'email' => 'nuria@test.com',
                'titulo' => 'Ensalada mediterranea',
                'pasos' => 'Cortar tomate, pepino y cebolla. Aliñar con aceite de oliva, sal y oregano.',
                'time' => 15,
                'foto' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=1200&q=80',
                'categories' => ['Almuerzo', 'Cena', 'Saludable', 'Mediterranea'],
                'ingredients' => ['Tomate', 'Pepino', 'Cebolla', 'Aceite de oliva', 'Sal', 'Oregano'],
            ],
            [
                'email' => 'nuria@test.com',
                'titulo' => 'Tortilla de patata',
                'pasos' => 'Pochar patata y cebolla, añadir huevo batido y cuajar en sartén.',
                'time' => 30,
                'foto' => 'https://images.unsplash.com/photo-1565895405138-6c3a1555da6a?auto=format&fit=crop&w=1200&q=80',
                'categories' => ['Cena', 'Plato principal', 'Facil'],
                'ingredients' => ['Patata', 'Cebolla', 'Huevo', 'Aceite de oliva', 'Sal'],
            ],
            [
                'email' => 'nuria@test.com',
                'titulo' => 'Pasta integral con atun',
                'pasos' => 'Cocer pasta, mezclar con atun y tomate salteado, ajustar sal y pimienta.',
                'time' => 25,
                'foto' => 'https://images.unsplash.com/photo-1621996346565-e3dbc646d9a9?auto=format&fit=crop&w=1200&q=80',
                'categories' => ['Almuerzo', 'Plato principal', 'Rapido (< 20 min)'],
                'ingredients' => ['Pasta integral', 'Atun', 'Tomate', 'Aceite de oliva', 'Sal', 'Pimienta negra'],
            ],
            [
                'email' => 'nuria@test.com',
                'titulo' => 'Yogur con fresa y platano',
                'pasos' => 'Servir yogur natural y añadir fruta troceada.',
                'time' => 8,
                'foto' => 'https://images.unsplash.com/photo-1488477181946-6428a0291777?auto=format&fit=crop&w=1200&q=80',
                'categories' => ['Desayuno', 'Snack', 'Facil'],
                'ingredients' => ['Yogur natural', 'Fresa', 'Platano'],
            ],
            [
                'email' => 'nuria@test.com',
                'titulo' => 'Pollo al horno con zanahoria',
                'pasos' => 'Hornear pechuga de pollo con zanahoria y condimentos hasta dorar.',
                'time' => 40,
                'foto' => 'https://images.unsplash.com/photo-1532550907401-a500c9a57435?auto=format&fit=crop&w=1200&q=80',
                'categories' => ['Cena', 'Alta en proteinas', 'Plato principal'],
                'ingredients' => ['Pechuga de pollo', 'Zanahoria', 'Aceite de oliva', 'Sal', 'Pimienta negra'],
            ],
            [
                'email' => 'pablo@test.com',
                'titulo' => 'Arroz con verduras',
                'pasos' => 'Sofreir verduras y cocer con arroz hasta que quede en su punto.',
                'time' => 35,
                'foto' => 'https://images.unsplash.com/photo-1512058564366-18510be2db19?auto=format&fit=crop&w=1200&q=80',
                'categories' => ['Almuerzo', 'Vegetariano', 'Economico'],
                'ingredients' => ['Arroz', 'Tomate', 'Pimiento rojo', 'Cebolla', 'Aceite de oliva', 'Sal'],
            ],
            [
                'email' => 'pablo@test.com',
                'titulo' => 'Lentejas guisadas',
                'pasos' => 'Cocer lentejas con verduras y ajustar condimentos al final.',
                'time' => 50,
                'foto' => 'https://images.unsplash.com/photo-1547592166-23ac45744acd?auto=format&fit=crop&w=1200&q=80',
                'categories' => ['Almuerzo', 'Plato principal', 'Batch cooking'],
                'ingredients' => ['Lentejas', 'Cebolla', 'Zanahoria', 'Ajo', 'Aceite de oliva', 'Sal'],
            ],
            [
                'email' => 'pablo@test.com',
                'titulo' => 'Salmon a la plancha',
                'pasos' => 'Marcar salmon en plancha caliente y servir con ensalada.',
                'time' => 20,
                'foto' => 'https://images.unsplash.com/photo-1467003909585-2f8a72700288?auto=format&fit=crop&w=1200&q=80',
                'categories' => ['Cena', 'Saludable', 'Alta en proteinas'],
                'ingredients' => ['Salmon', 'Lechuga', 'Tomate', 'Aceite de oliva', 'Sal'],
            ],
            [
                'email' => 'pablo@test.com',
                'titulo' => 'Bocadillo integral de atun',
                'pasos' => 'Rellenar pan integral con atun y tomate en rodajas.',
                'time' => 10,
                'foto' => 'https://images.unsplash.com/photo-1550507992-eb63ffee0847?auto=format&fit=crop&w=1200&q=80',
                'categories' => ['Desayuno', 'Snack', 'Rapido (< 20 min)'],
                'ingredients' => ['Pan integral', 'Atun', 'Tomate', 'Aceite de oliva'],
            ],
            [
                'email' => 'pablo@test.com',
                'titulo' => 'Ensalada de garbanzos',
                'pasos' => 'Mezclar garbanzos cocidos con tomate, cebolla y aliño.',
                'time' => 18,
                'foto' => 'https://images.unsplash.com/photo-1514995669114-6081e934b693?auto=format&fit=crop&w=1200&q=80',
                'categories' => ['Almuerzo', 'Saludable', 'Sin procesados'],
                'ingredients' => ['Garbanzos', 'Tomate', 'Cebolla', 'Aceite de oliva', 'Sal'],
            ],
        ];

        foreach ($recipes as $item) {
            $userId = $usersByEmail[$item['email']];
            $now = now();
            $recipeId = (int) DB::table('recipes')->insertGetId([
                'user_id' => $userId,
                'titulo' => $item['titulo'],
                'pasos' => $item['pasos'],
                'time' => $item['time'],
                'foto' => $item['foto'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $recipe = Recipe::query()->find($recipeId);
            if (!$recipe) {
                continue;
            }

            $categoryIds = Category::query()
                ->whereIn('name', $item['categories'])
                ->pluck('id')
                ->all();

            $ingredientIds = Ingredient::query()
                ->whereIn('nombre', $item['ingredients'])
                ->pluck('id')
                ->all();

            $recipe->categories()->sync($categoryIds);
            $recipe->ingredients()->sync($ingredientIds);
        }
    }
}
