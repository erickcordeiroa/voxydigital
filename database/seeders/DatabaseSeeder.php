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

        User::create([
            "name" => "Erick Cordeiro",
            "email" => "ferja@gmail.com",
            "password" => "erick2020",
            "tenant_id" => $tenant->id,
        ]);
    }
}
