<?php

namespace Database\Seeders;

use App\Models\Contrat;
use App\Models\DeclarationIncident;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class IncidentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer tous les contrats
        $contrats = Contrat::all();

        if ($contrats->isEmpty()) {
            $this->command->info('Aucun contrat trouvé. Impossible de créer des incidents.');
            return;
        }

        $typesIncidents = [
            'Panne d\'électricité',
            'Baisse de tension',
            'Surtension',
            'Coupure programmée',
            'Problème de facturation',
            'Compteur défectueux'
        ];
        
        $statuts = ['nouveau', 'en cours', 'résolu'];
        
        $descriptions = [
            'Le client signale une interruption complète de service.',
            'Le client constate des variations de tension affectant ses équipements.',
            'Plusieurs appareils ont été endommagés suite à une surtension.',
            'Le client n\'a pas été informé d\'une coupure programmée.',
            'Montant facturé ne correspond pas à la consommation réelle.',
            'Le compteur affiche des valeurs erronées depuis plusieurs jours.'
        ];

        // Créer entre 0 et 3 incidents par contrat
        foreach ($contrats as $contrat) {
            $nbIncidents = rand(0, 3);
            
            for ($i = 0; $i < $nbIncidents; $i++) {
                $indexType = array_rand($typesIncidents);
                $dateCreation = Carbon::parse($contrat->date_debut)
                    ->addDays(rand(1, 90));
                
                // Si date dans le futur, passer
                if ($dateCreation->isFuture()) {
                    continue;
                }
                
                $statut = $statuts[array_rand($statuts)];
                
                // Date de résolution uniquement si l'incident est résolu
                $dateResolution = null;
                if ($statut === 'résolu') {
                    $dateResolution = Carbon::parse($dateCreation)
                        ->addDays(rand(1, 14));
                        
                    // Si date dans le futur, passer à aujourd'hui
                    if ($dateResolution->isFuture()) {
                        $dateResolution = Carbon::now();
                    }
                }
                
                // Utiliser les attributs qui existent dans le modèle DeclarationIncident
                DeclarationIncident::create([
                    'titre' => $typesIncidents[$indexType],
                    'details' => $descriptions[$indexType],
                    'niveau' => rand(1, 3), // Niveau d'urgence: 1 (faible) à 3 (élevé)
                    'statut' => $statut,
                    'user_id' => $contrat->user_id,
                    'contrat_id' => $contrat->id,
                    'created_at' => $dateCreation,
                    'updated_at' => $dateResolution ?? $dateCreation
                ]);
            }
        }
        
        $this->command->info('Incidents générés avec succès !');
    }
}
