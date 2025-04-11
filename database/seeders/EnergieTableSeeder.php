<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Energie;
use App\Models\User;
use App\Models\Categorie;
use Illuminate\Support\Str;

class EnergieTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendeurs = User::role('vendeur')->get();
        $categories = Categorie::all();

        // Énergies solaires
        $categorieSolaire = $categories->where('nom', 'Solaire')->first();
        
        if ($categorieSolaire && $vendeurs->count() > 0) {
            Energie::create([
                'titre' => 'Panneaux solaires haute performance',
                'stock_kwh' => 5000,
                'slug' => Str::slug('Panneaux solaires haute performance'),
                'localisation' => 'Sud de la France',
                'categorie_id' => $categorieSolaire->id,
                'user_id' => $vendeurs->random()->id
            ]);

            Energie::create([
                'titre' => 'Installation solaire domestique',
                'stock_kwh' => 2500,
                'slug' => Str::slug('Installation solaire domestique'),
                'localisation' => 'Région PACA',
                'categorie_id' => $categorieSolaire->id,
                'user_id' => $vendeurs->random()->id
            ]);
        }

        // Énergies hydrauliques
        $categorieHydraulique = $categories->where('nom', 'Hydraulique')->first();
        
        if ($categorieHydraulique && $vendeurs->count() > 0) {
            Energie::create([
                'titre' => 'Micro-centrale hydraulique',
                'stock_kwh' => 8000,
                'slug' => Str::slug('Micro-centrale hydraulique'),
                'localisation' => 'Alpes',
                'categorie_id' => $categorieHydraulique->id,
                'user_id' => $vendeurs->random()->id
            ]);
        }

        // Énergies éoliennes
        $categorieEolienne = $categories->where('nom', 'Éolienne')->first();
        
        if ($categorieEolienne && $vendeurs->count() > 0) {
            Energie::create([
                'titre' => 'Éolienne domestique 3kW',
                'stock_kwh' => 3500,
                'slug' => Str::slug('Éolienne domestique 3kW'),
                'localisation' => 'Normandie',
                'categorie_id' => $categorieEolienne->id,
                'user_id' => $vendeurs->random()->id
            ]);
        }

        // Énergies géothermiques
        $categorieGeothermique = $categories->where('nom', 'Géothermique')->first();
        
        if ($categorieGeothermique && $vendeurs->count() > 0) {
            Energie::create([
                'titre' => 'Pompe à chaleur géothermique',
                'stock_kwh' => 4200,
                'slug' => Str::slug('Pompe à chaleur géothermique'),
                'localisation' => 'Centre France',
                'categorie_id' => $categorieGeothermique->id,
                'user_id' => $vendeurs->random()->id
            ]);
        }
    }
}
