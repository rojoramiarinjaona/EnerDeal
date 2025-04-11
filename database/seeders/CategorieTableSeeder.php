<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categorie;
use Illuminate\Support\Str;

class CategorieTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Solaire',
            'Hydraulique',
            'Éolienne',
            'Géothermique'
        ];

        foreach ($categories as $categorie) {
            Categorie::create([
                'nom' => $categorie,
                'slug' => Str::slug($categorie)
            ]);
        }
    }
}
