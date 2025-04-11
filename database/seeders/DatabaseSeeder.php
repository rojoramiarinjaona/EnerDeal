<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            UsersTableSeeder::class,
            CategoriesTableSeeder::class,
            EnergiesTableSeeder::class,
            FormulesTableSeeder::class,
            ContratsTableSeeder::class,
            FacturesTableSeeder::class,
            IncidentsTableSeeder::class,
            DemandesTableSeeder::class,
        ]);

        // User::factory(10)->create();

        User::factory()->create([
            'nom' => 'Test',
            'prenom' => 'User',
            'email' => 'test@example.com',
            'lieu_de_residence' => 'Test City',
        ]);
    }
}
