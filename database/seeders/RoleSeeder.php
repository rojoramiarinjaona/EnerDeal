<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Créer les rôles
        $roles = [
            'admin',
            'vendeur',
            'client'
        ];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        // Créer les permissions
        $permissions = [
            // Permissions pour les énergies
            'voir-energies',
            'creer-energies',
            'modifier-energies',
            'supprimer-energies',
            
            // Permissions pour les formules
            'voir-formules',
            'creer-formules',
            'modifier-formules',
            'supprimer-formules',
            
            // Permissions pour les contrats
            'voir-contrats',
            'creer-contrats',
            'modifier-contrats',
            'supprimer-contrats',
            
            // Permissions pour les factures
            'voir-factures',
            'creer-factures',
            'modifier-factures',
            'supprimer-factures',
            
            // Permissions pour les incidents
            'voir-incidents',
            'creer-incidents',
            'modifier-incidents',
            'supprimer-incidents',
            
            // Permissions pour les utilisateurs
            'voir-utilisateurs',
            'creer-utilisateurs',
            'modifier-utilisateurs',
            'supprimer-utilisateurs',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Attribuer toutes les permissions à l'admin
        $adminRole = Role::findByName('admin');
        $adminRole->givePermissionTo(Permission::all());

        // Attribuer les permissions aux vendeurs
        $vendeurRole = Role::findByName('vendeur');
        $vendeurRole->givePermissionTo([
            'voir-energies', 'creer-energies', 'modifier-energies', 'supprimer-energies',
            'voir-formules', 'creer-formules', 'modifier-formules', 'supprimer-formules',
            'voir-contrats', 'modifier-contrats',
            'voir-factures', 'creer-factures', 'modifier-factures',
            'voir-incidents', 'modifier-incidents',
        ]);

        // Attribuer les permissions aux clients
        $clientRole = Role::findByName('client');
        $clientRole->givePermissionTo([
            'voir-energies',
            'voir-formules',
            'voir-contrats', 'creer-contrats',
            'voir-factures',
            'voir-incidents', 'creer-incidents',
        ]);

        $this->command->info('Rôles et permissions créés avec succès !');
    }
} 