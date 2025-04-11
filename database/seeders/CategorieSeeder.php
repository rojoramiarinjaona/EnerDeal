<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categorie;
use Illuminate\Support\Str;

class CategorieSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Solaire',
            'Éolienne',
            'Hydraulique',
            'Géothermique'
        ];

        foreach ($categories as $categorie) {
            Categorie::create([
                'nom' => $categorie,
                'slug' => Str::slug($categorie)
            ]);
        }

        $this->command->info('Catégories de test créées avec succès !');
    }
} 