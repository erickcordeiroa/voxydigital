<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Camisetas',
                'description' => 'Variedade de camisetas para todos os estilos.',
                'status' => true,
                'tenant_id' => 3,
            ],
            [
                'name' => 'Calças',
                'description' => 'Diversos modelos de calças masculinas e femininas.',
                'status' => true,
                'tenant_id' => 3,
            ],
            [
                'name' => 'Vestidos',
                'description' => 'Vestidos casuais e sociais para todas as ocasiões.',
                'status' => true,
                'tenant_id' => 3,
            ],
            [
                'name' => 'Jaquetas',
                'description' => 'Jaquetas e casacos para todas as estações.',
                'status' => true,
                'tenant_id' => 3,
            ],
            [
                'name' => 'Tênis',
                'description' => 'Modelos confortáveis e estilosos de tênis.',
                'status' => true,
                'tenant_id' => 3,
            ],
            [
                'name' => 'Botas',
                'description' => 'Botas masculinas e femininas para todas as ocasiões.',
                'status' => true,
                'tenant_id' => 3,
            ],
            [
                'name' => 'Sandálias',
                'description' => 'Sandálias e chinelos variados para conforto e estilo.',
                'status' => true,
                'tenant_id' => 3,
            ],
            [
                'name' => 'Acessórios',
                'description' => 'Bolsas, cintos, carteiras e outros acessórios.',
                'status' => true,
                'tenant_id' => 3,
            ],
            [
                'name' => 'Infantil',
                'description' => 'Roupas e calçados para o público infantil.',
                'status' => true,
                'tenant_id' => 3,
            ],
            [
                'name' => 'Promoções',
                'description' => 'Produtos em promoção e ofertas especiais.',
                'status' => true,
                'tenant_id' => 3,
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate([
                'slug' => Str::slug($category['name']),
            ], [
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'status' => $category['status'],
                'tenant_id' => $category['tenant_id'],
            ]);
        }
    }   
}
