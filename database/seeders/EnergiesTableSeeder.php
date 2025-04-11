<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Energie;
use App\Models\User;
use App\Models\Categorie;
use Illuminate\Support\Str;

class EnergiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer tous les vendeurs
        $vendeurs = User::role('vendeur')->get();
        
        if ($vendeurs->isEmpty()) {
            $this->command->info('Aucun vendeur trouvé. Création d\'énergies impossible.');
            return;
        }
        
        // Récupérer toutes les catégories
        $categories = Categorie::all();
        
        if ($categories->isEmpty()) {
            $this->command->info('Aucune catégorie trouvée. Création d\'énergies impossible.');
            return;
        }
        
        // Créer quelques énergies pour chaque vendeur
        foreach ($vendeurs as $vendeur) {
            for ($i = 1; $i <= 3; $i++) {
                $categorie = $categories->random();
                $titre = $categorie->nom . ' - ' . $vendeur->nom . ' ' . $i;
                
                Energie::create([
                    'titre' => $titre,
                    'slug' => Str::slug($titre),
                    'stock_kwh' => rand(1000, 10000),
                    'localisation' => 'Région ' . rand(1, 13),
                    'categorie_id' => $categorie->id,
                    'user_id' => $vendeur->id,
                ]);
            }
        }
    }
}
