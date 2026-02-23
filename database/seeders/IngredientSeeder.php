<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IngredientSeeder extends Seeder
{
    public function run(): void
    {
        $ingredients = [
            ['nombre' => 'Tomate', 'category' => 'Hortalizas de fruto'],
            ['nombre' => 'Lechuga', 'category' => 'Verduras de hoja verde'],
            ['nombre' => 'Cebolla', 'category' => 'Hortalizas de raiz'],
            ['nombre' => 'Ajo', 'category' => 'Hortalizas de raiz'],
            ['nombre' => 'Pimiento rojo', 'category' => 'Hortalizas de fruto'],
            ['nombre' => 'Pepino', 'category' => 'Hortalizas de fruto'],
            ['nombre' => 'Zanahoria', 'category' => 'Hortalizas de raiz'],
            ['nombre' => 'Patata', 'category' => 'Tuberculos'],
            ['nombre' => 'Arroz', 'category' => 'Arroces'],
            ['nombre' => 'Pasta integral', 'category' => 'Pastas'],
            ['nombre' => 'Lentejas', 'category' => 'Legumbres secas'],
            ['nombre' => 'Garbanzos', 'category' => 'Legumbres secas'],
            ['nombre' => 'Pechuga de pollo', 'category' => 'Aves'],
            ['nombre' => 'Carne picada de ternera', 'category' => 'Carne roja'],
            ['nombre' => 'Salmon', 'category' => 'Pescado azul'],
            ['nombre' => 'Atun', 'category' => 'Pescado azul'],
            ['nombre' => 'Huevo', 'category' => 'Huevos'],
            ['nombre' => 'Queso rallado', 'category' => 'Quesos'],
            ['nombre' => 'Yogur natural', 'category' => 'Yogures'],
            ['nombre' => 'Aceite de oliva', 'category' => 'Aceites vegetales'],
            ['nombre' => 'Sal', 'category' => 'Condimentos y especias'],
            ['nombre' => 'Pimienta negra', 'category' => 'Especias'],
            ['nombre' => 'Oregano', 'category' => 'Hierbas aromaticas'],
            ['nombre' => 'Pan integral', 'category' => 'Panes'],
            ['nombre' => 'Platano', 'category' => 'Frutas tropicales'],
            ['nombre' => 'Fresa', 'category' => 'Frutas del bosque'],
        ];

        foreach ($ingredients as $item) {
            $categoryId = Category::query()
                ->where('name', $item['category'])
                ->value('id');

            if (!$categoryId) {
                continue;
            }

            $now = now();
            $existing = DB::table('ingredients')
                ->where('nombre', $item['nombre'])
                ->value('id');

            if ($existing) {
                DB::table('ingredients')
                    ->where('id', $existing)
                    ->update([
                        'category_id' => $categoryId,
                        'updated_at' => $now,
                    ]);
            } else {
                DB::table('ingredients')->insert([
                    'nombre' => $item['nombre'],
                    'category_id' => $categoryId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
