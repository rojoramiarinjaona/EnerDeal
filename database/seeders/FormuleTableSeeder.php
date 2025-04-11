<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Formule;
use App\Models\Energie;
use Illuminate\Support\Str;

class FormuleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $energies = Energie::all();

        foreach ($energies as $energie) {
            // Formule basique
            Formule::create([
                'ref' => 'FORM-' . strtoupper(Str::random(6)),
                'intitule' => 'Formule Basique - ' . $energie->titre,
                'quantite_kwh' => 100,
                'duree' => '6 mois',
                'taxite' => 5.5,
                'prix_kwh' => 0.15,
                'details_contrat' => 'Contrat standard pour petite consommation.',
                'conditions_resiliation' => 'Préavis de 30 jours. Frais de résiliation: 50€ si moins de 3 mois.',
                'modalite_livraison' => 'Injection directe dans le réseau existant.',
                'energie_id' => $energie->id,
                'user_id' => $energie->user_id
            ]);

            // Formule standard
            Formule::create([
                'ref' => 'FORM-' . strtoupper(Str::random(6)),
                'intitule' => 'Formule Standard - ' . $energie->titre,
                'quantite_kwh' => 250,
                'duree' => '12 mois',
                'taxite' => 5.5,
                'prix_kwh' => 0.13,
                'details_contrat' => 'Contrat intermédiaire idéal pour une consommation moyenne.',
                'conditions_resiliation' => 'Préavis de 45 jours. Frais de résiliation: 80€ si moins de 6 mois.',
                'modalite_livraison' => 'Injection directe dans le réseau existant avec monitoring mensuel.',
                'energie_id' => $energie->id,
                'user_id' => $energie->user_id
            ]);

            // Formule premium
            Formule::create([
                'ref' => 'FORM-' . strtoupper(Str::random(6)),
                'intitule' => 'Formule Premium - ' . $energie->titre,
                'quantite_kwh' => 500,
                'duree' => '24 mois',
                'taxite' => 5.5,
                'prix_kwh' => 0.11,
                'details_contrat' => 'Contrat avancé pour grande consommation avec tarifs préférentiels.',
                'conditions_resiliation' => 'Préavis de 60 jours. Frais de résiliation: 120€ si moins de 12 mois.',
                'modalite_livraison' => 'Injection prioritaire dans le réseau avec monitoring en temps réel et assistance 24/7.',
                'energie_id' => $energie->id,
                'user_id' => $energie->user_id
            ]);
        }
    }
}
