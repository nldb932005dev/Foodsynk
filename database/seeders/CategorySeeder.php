<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // A) Tipo de alimento
            'Verduras y hortalizas',
            'Verduras de hoja verde',
            'Hortalizas de raiz',
            'Hortalizas de fruto',
            'Hortalizas de tallo',
            'Cruciferas',
            'Legumbres frescas',
            'Tuberculos',

            'Frutas',
            'Frutas con hueso',
            'Frutas citricas',
            'Frutas tropicales',
            'Frutas del bosque',
            'Frutas deshidratadas',

            'Cereales y derivados',
            'Cereales con gluten',
            'Cereales sin gluten',
            'Harinas',
            'Panes',
            'Pastas',
            'Arroces',

            'Legumbres',
            'Legumbres secas',
            'Legumbres cocidas',

            'Lacteos',
            'Leche',
            'Quesos',
            'Yogures',
            'Nata y cremas',
            'Mantequilla',

            'Proteinas animales',
            'Carne roja',
            'Carne blanca',
            'Aves',
            'Cerdo',
            'Cordero',

            'Pescados y mariscos',
            'Pescado blanco',
            'Pescado azul',
            'Mariscos',
            'Moluscos',
            'Crustaceos',

            'Huevos',

            'Frutos secos y semillas',
            'Frutos secos',
            'Semillas',

            'Condimentos y especias',
            'Especias',
            'Hierbas aromaticas',
            'Salsas',
            'Vinagres',

            'Endulzantes',
            'Azucar',
            'Miel',
            'Edulcorantes',

            'Grasas',
            'Aceites vegetales',
            'Grasas animales',

            'Conservas',
            'Conservas vegetales',
            'Conservas de pescado',

            'Bebidas',
            'Bebidas vegetales',
            'Zumos',
            'Refrescos',
            'Bebidas alcoholicas',

            // B) Alergias / intolerancias
            'Sin gluten',
            'Con gluten',
            'Sin lactosa',
            'Con lactosa',
            'Sin proteina animal',
            'Sin huevo',
            'Sin frutos secos',
            'Sin soja',
            'Sin marisco',
            'Sin pescado',
            'Sin azucar anadido',
            'Bajo en sodio',
            'Sin conservantes',
            'Sin colorantes',

            // C) Tipo de dieta
            'Vegetariano',
            'Vegano',
            'Ovo-vegetariano',
            'Lacto-vegetariano',
            'Pescetariano',
            'Keto',
            'Paleo',
            'Mediterranea',
            'Alta en proteinas',
            'Baja en carbohidratos',
            'Baja en grasas',
            'Baja en calorias',
            'Hipocalorica',
            'Hipercalorica',
            'Sin procesados',

            // D) Funcionales / UX
            'Desayuno',
            'Almuerzo',
            'Cena',
            'Postre',
            'Snack',
            'Plato principal',
            'Guarnicion',
            'Aperitivo',
            'Rapido (< 20 min)',
            'Facil',
            'Economico',
            'Saludable',
            'Infantil',
            'Para eventos',
            'Batch cooking',
        ];

        foreach (array_unique($categories) as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}
