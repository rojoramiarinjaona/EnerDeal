<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contrat;
use App\Models\User;
use App\Models\Formule;
use Carbon\Carbon;

class ContratSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer quelques clients et formules existants
        $clients = User::role('client')->take(5)->get();
        $formules = Formule::take(5)->get();

        if ($clients->isEmpty() || $formules->isEmpty()) {
            $this->command->info('Aucun client ou formule trouvé. Veuillez d\'abord créer des clients et des formules.');
            return;
        }

        $statuts = ['en attente', 'actif', 'résilié'];

        foreach ($clients as $client) {
            // Créer 1 à 3 contrats par client
            $nombreContrats = rand(1, 3);
            
            for ($i = 0; $i < $nombreContrats; $i++) {
                $formule = $formules->random();
                $dateDebut = Carbon::now()->subMonths(rand(1, 6));
                
                Contrat::create([
                    'date_debut' => $dateDebut,
                    'date_fin' => $dateDebut->copy()->addMonths(rand(6, 24)),
                    'statut' => $statuts[array_rand($statuts)],
                    'formule_id' => $formule->id,
                    'user_id' => $client->id,
                ]);
            }
        }

        $this->command->info('Contrats de test créés avec succès !');
    }
} 