<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Contrat;
use App\Models\Formule;
use App\Models\User;
use Carbon\Carbon;

class ContratsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer tous les clients
        $clients = User::role('client')->get();
        
        if ($clients->isEmpty()) {
            $this->command->info('Aucun client trouvé. Création de contrats impossible.');
            return;
        }
        
        // Récupérer toutes les formules
        $formules = Formule::all();
        
        if ($formules->isEmpty()) {
            $this->command->info('Aucune formule trouvée. Création de contrats impossible.');
            return;
        }
        
        // Liste des statuts possibles
        $statuts = ['en attente', 'actif', 'résilié'];
        
        // Créer des contrats pour chaque client
        foreach ($clients as $client) {
            // Nombre aléatoire de contrats par client (1 à 3)
            $nbContrats = rand(1, 3);
            
            for ($i = 0; $i < $nbContrats; $i++) {
                // Choisir une formule aléatoire
                $formule = $formules->random();
                
                // Définir les dates
                $dateDebut = Carbon::now()->subDays(rand(0, 365));
                
                // Déterminer le statut
                $statut = $statuts[array_rand($statuts)];
                
                // Créer le contrat
                Contrat::create([
                    'date_debut' => $dateDebut,
                    'statut' => $statut,
                    'formule_id' => $formule->id,
                    'user_id' => $client->id,
                ]);
            }
        }
    }
}
