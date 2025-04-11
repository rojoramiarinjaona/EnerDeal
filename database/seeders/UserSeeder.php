<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Créer un administrateur
        $admin = User::create([
            'nom' => 'Admin',
            'prenom' => 'System',
            'email' => 'admin@enerdeal.com',
            'password' => Hash::make('password'),
            'lieu_de_residence' => 'Paris',
        ]);
        $admin->assignRole('admin');

        // Créer quelques vendeurs
        $vendeurs = [
            ['nom' => 'Dupont', 'prenom' => 'Jean', 'email' => 'jean@enerdeal.com', 'lieu_de_residence' => 'Lyon'],
            ['nom' => 'Martin', 'prenom' => 'Marie', 'email' => 'marie@enerdeal.com', 'lieu_de_residence' => 'Marseille'],
            ['nom' => 'Durand', 'prenom' => 'Pierre', 'email' => 'pierre@enerdeal.com', 'lieu_de_residence' => 'Bordeaux'],
        ];

        foreach ($vendeurs as $vendeur) {
            $user = User::create([
                'nom' => $vendeur['nom'],
                'prenom' => $vendeur['prenom'],
                'email' => $vendeur['email'],
                'password' => Hash::make('password'),
                'lieu_de_residence' => $vendeur['lieu_de_residence'],
            ]);
            $user->assignRole('vendeur');
        }

        // Créer quelques clients
        $clients = [
            ['nom' => 'Bernard', 'prenom' => 'Alice', 'email' => 'alice@enerdeal.com', 'lieu_de_residence' => 'Lille'],
            ['nom' => 'Petit', 'prenom' => 'Thomas', 'email' => 'thomas@enerdeal.com', 'lieu_de_residence' => 'Toulouse'],
            ['nom' => 'Moreau', 'prenom' => 'Sophie', 'email' => 'sophie@enerdeal.com', 'lieu_de_residence' => 'Nantes'],
            ['nom' => 'Dubois', 'prenom' => 'Lucas', 'email' => 'lucas@enerdeal.com', 'lieu_de_residence' => 'Strasbourg'],
            ['nom' => 'Lambert', 'prenom' => 'Emma', 'email' => 'emma@enerdeal.com', 'lieu_de_residence' => 'Rennes'],
        ];

        foreach ($clients as $client) {
            $user = User::create([
                'nom' => $client['nom'],
                'prenom' => $client['prenom'],
                'email' => $client['email'],
                'password' => Hash::make('password'),
                'lieu_de_residence' => $client['lieu_de_residence'],
            ]);
            $user->assignRole('client');
        }

        $this->command->info('Utilisateurs de test créés avec succès !');
    }
} 