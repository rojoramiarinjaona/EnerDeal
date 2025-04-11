<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Formule;
use App\Models\Energie;
use App\Models\User;

class FormuleSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer les vendeurs et les énergies
        $vendeurs = User::role('vendeur')->get();
        $energies = Energie::all();

        if ($vendeurs->isEmpty() || $energies->isEmpty()) {
            $this->command->info('Aucun vendeur ou énergie trouvé. Veuillez d\'abord créer des vendeurs et des énergies.');
            return;
        }

        $durees = ['6 mois', '1 an', '2 ans'];
        $modalites = ['Livraison mensuelle', 'Livraison trimestrielle', 'Livraison semestrielle'];

        foreach ($energies as $energie) {
            $vendeur = $vendeurs->random();
            
            // Créer 2 à 4 formules par énergie
            $nombreFormules = rand(2, 4);
            
            for ($i = 0; $i < $nombreFormules; $i++) {
                Formule::create([
                    'ref' => 'F' . str_pad($energie->id, 3, '0', STR_PAD_LEFT) . str_pad($i + 1, 2, '0', STR_PAD_LEFT),
                    'nom' => 'Formule ' . ($i + 1) . ' - ' . $energie->titre,
                    'quantite_kwh' => rand(1000, 5000),
                    'duree' => $durees[array_rand($durees)],
                    'taxite' => rand(5, 15) / 100,
                    'prix_kwh' => rand(10, 30) / 100,
                    'details_contrat' => 'Détails du contrat pour la formule ' . ($i + 1),
                    'conditions_resiliation' => 'Conditions de résiliation pour la formule ' . ($i + 1),
                    'modalite_livraison' => $modalites[array_rand($modalites)],
                    'energie_id' => $energie->id,
                    'user_id' => $vendeur->id,
                ]);
            }
        }

        $this->command->info('Formules de test créées avec succès !');
    }
} 