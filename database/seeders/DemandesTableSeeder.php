<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Demande;
use App\Models\User;
use Carbon\Carbon;

class DemandesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer des utilisateurs pour les demandes de vendeurs
        $users = [
            [
                'nom' => 'Martin',
                'prenom' => 'Louise',
                'email' => 'louise.martin@example.com',
                'password' => bcrypt('password'),
                'lieu_de_residence' => 'Nantes',
            ],
            [
                'nom' => 'Dupont',
                'prenom' => 'Thomas',
                'email' => 'thomas.dupont@example.com',
                'password' => bcrypt('password'),
                'lieu_de_residence' => 'Lyon',
            ],
            [
                'nom' => 'Leroy',
                'prenom' => 'Julie',
                'email' => 'julie.leroy@example.com',
                'password' => bcrypt('password'),
                'lieu_de_residence' => 'Bordeaux',
            ],
        ];

        // Créer chaque utilisateur et attribuer le rôle client
        foreach ($users as $userData) {
            $user = User::create($userData);
            $user->assignRole('client');
            
            // Créer une demande de vendeur pour cet utilisateur
            Demande::create([
                'status' => 'en_attente',
                'user_id' => $user->id,
                'created_at' => Carbon::now()->subDays(rand(1, 5)),
                'updated_at' => Carbon::now(),
            ]);
        }
        
        $this->command->info('Demandes de vendeurs créées avec succès !');
    }
}
