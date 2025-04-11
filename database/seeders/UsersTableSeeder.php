<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Création d'un admin
        $admin = User::create([
            'nom' => 'Admin',
            'prenom' => 'Système',
            'email' => 'admin@energix.com',
            'password' => Hash::make('password'),
            'lieu_de_residence' => 'Paris',
        ]);
        $admin->assignRole('admin');

        // Création de vendeurs
        $vendeur1 = User::create([
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'email' => 'vendeur1@energix.com',
            'password' => Hash::make('password'),
            'lieu_de_residence' => 'Lyon',
        ]);
        $vendeur1->assignRole('vendeur');

        $vendeur2 = User::create([
            'nom' => 'Martin',
            'prenom' => 'Sophie',
            'email' => 'vendeur2@energix.com',
            'password' => Hash::make('password'),
            'lieu_de_residence' => 'Marseille',
        ]);
        $vendeur2->assignRole('vendeur');

        // Création de clients
        $client1 = User::create([
            'nom' => 'Petit',
            'prenom' => 'Marie',
            'email' => 'client1@example.com',
            'password' => Hash::make('password'),
            'lieu_de_residence' => 'Bordeaux',
        ]);
        $client1->assignRole('client');

        $client2 = User::create([
            'nom' => 'Durand',
            'prenom' => 'Pierre',
            'email' => 'client2@example.com',
            'password' => Hash::make('password'),
            'lieu_de_residence' => 'Lille',
        ]);
        $client2->assignRole('client');
    }
}
