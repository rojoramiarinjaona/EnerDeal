<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Formule;
use App\Models\Energie;
use App\Models\User;
use Illuminate\Support\Str;

class FormulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer toutes les énergies
        $energies = Energie::all();
        
        if ($energies->isEmpty()) {
            $this->command->info('Aucune énergie trouvée. Création de formules impossible.');
            return;
        }
        
        // Textes pour les formules
        $detailsContrat = "Ce contrat est soumis aux conditions générales de vente disponibles sur notre site. La livraison d'énergie s'effectue de manière continue selon les termes convenus. Le client s'engage à respecter les conditions d'utilisation.";
        
        $conditionsResiliation = "Vous pouvez résilier ce contrat à tout moment après la période minimale d'engagement de 12 mois. Un préavis de 30 jours est requis. Des frais de résiliation anticipée peuvent s'appliquer si vous résiliez avant la fin de la période d'engagement.";
        
        $modaliteLivraison = "La livraison de l'énergie s'effectue de manière continue via le réseau de distribution existant. La qualité et la continuité de la fourniture sont garanties par nos services, sauf en cas de force majeure ou d'intervention technique nécessaire.";
        
        // Créer des formules pour chaque énergie
        foreach ($energies as $energie) {
            $vendeur = User::find($energie->user_id);
            
            // Créer 2 formules par énergie
            for ($i = 1; $i <= 2; $i++) {
                $intitule = 'Formule ' . ucfirst($energie->categorie->nom) . ' ' . $i . ' - ' . $vendeur->nom;
                $quantite = rand(100, 1000);
                $prix = rand(5, 20) / 100; // Entre 0.05 et 0.20 €
                
                Formule::create([
                    'ref' => 'FORM-' . strtoupper(Str::random(6)),
                    'intitule' => $intitule,
                    'quantite_kwh' => $quantite,
                    'duree' => rand(1, 12) * 30, // Durée en jours (1-12 mois)
                    'taxite' => 20, // TVA à 20%
                    'prix_kwh' => $prix,
                    'details_contrat' => $detailsContrat,
                    'conditions_resiliation' => $conditionsResiliation,
                    'modalite_livraison' => $modaliteLivraison,
                    'energie_id' => $energie->id,
                    'user_id' => $vendeur->id,
                ]);
            }
        }
    }
}
