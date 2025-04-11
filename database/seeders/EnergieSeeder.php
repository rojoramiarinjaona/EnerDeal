<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Energie;
use App\Models\Categorie;
use App\Models\User;
use Illuminate\Support\Str;

class EnergieSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer les vendeurs et les catégories
        $vendeurs = User::role('vendeur')->get();
        $categories = Categorie::all();

        if ($vendeurs->isEmpty() || $categories->isEmpty()) {
            $this->command->info('Aucun vendeur ou catégorie trouvé. Veuillez d\'abord créer des vendeurs et des catégories.');
            return;
        }

        $typesEnergie = [
            'Solaire' => ['Panneaux solaires', 'Installation photovoltaïque', 'Centrale solaire'],
            'Éolienne' => ['Éolienne domestique', 'Parc éolien', 'Mini-éolienne'],
            'Hydraulique' => ['Micro-centrale', 'Turbine hydraulique', 'Barrage'],
            'Géothermique' => ['Pompe à chaleur', 'Forage géothermique', 'Échangeur de chaleur']
        ];

        foreach ($typesEnergie as $type => $sousTypes) {
            $categorie = $categories->where('nom', $type)->first();
            
            if (!$categorie) {
                continue;
            }

            foreach ($sousTypes as $sousType) {
                $vendeur = $vendeurs->random();
                $titre = $sousType . ' - ' . Str::random(5);
                
                Energie::create([
                    'titre' => $titre,
                    'slug' => Str::slug($titre),
                    'stock_kwh' => rand(5000, 20000),
                    'localisation' => $vendeur->lieu_de_residence,
                    'categorie_id' => $categorie->id,
                    'user_id' => $vendeur->id,
                ]);
            }
        }

        $this->command->info('Énergies de test créées avec succès !');
    }
} 