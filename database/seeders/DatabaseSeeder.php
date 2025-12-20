<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $tenant = Tenant::first();

        if (!$tenant) {
            $this->command->error('Nenhum tenant encontrado. Por favor, crie um tenant primeiro.');
            return;
        }

        User::firstOrCreate(
            [
                'email' => 'ferja@gmail.com',
            ],
            [
                'name' => 'Erick Cordeiro',
                'password' => 'erick2020',
                'tenant_id' => $tenant->id,
            ]
        );

        // Seed de assinaturas
        $this->call([
            SubscriptionSeeder::class,
        ]);
    }
}
