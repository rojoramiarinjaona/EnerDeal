<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Facture;
use App\Models\Contrat;
use Carbon\Carbon;
use Illuminate\Support\Str;

class FacturesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer tous les contrats existants
        $contrats = Contrat::all();

        if ($contrats->isEmpty()) {
            $this->command->info('Aucun contrat trouvé. Impossible de créer des factures.');
            return;
        }

        foreach ($contrats as $contrat) {
            // Générer entre 1 et 3 factures par contrat
            $nombreFactures = rand(1, 3);

            for ($i = 0; $i < $nombreFactures; $i++) {
                // S'assurer que les factures ne sont pas créées pour des dates futures
                if (Carbon::parse($contrat->date_debut)->isFuture()) {
                    continue;
                }

                // Statut de paiement aléatoire
                $statuts = ['payée', 'en attente', 'en retard'];
                $statut = $statuts[array_rand($statuts)];

                // Date de paiement (null si pas payée)
                $datePaiement = null;
                if ($statut === 'payée') {
                    // Si payée, date de paiement entre la date de début du contrat et aujourd'hui
                    $datePaiement = Carbon::parse($contrat->date_debut)
                        ->addDays(rand(5, 30))
                        ->format('Y-m-d');
                }

                // Montant aléatoire
                $montant = rand(100, 1000);

                // Créer la facture
                Facture::create([
                    'montant' => $montant,
                    'date_paiement' => $datePaiement,
                    'statut_paiement' => $statut,
                    'contrat_id' => $contrat->id,
                    'user_id' => $contrat->user_id,
                    'formule_id' => $contrat->formule_id,
                ]);
            }
        }

        $this->command->info('Factures générées avec succès !');
    }
}
