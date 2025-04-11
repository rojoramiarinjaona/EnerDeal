<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Pour les formules
            'view formules',
            'create formule',
            'edit formule',
            'delete formule',
            
            // Pour les contrats
            'view contrats',
            'create contrat',
            'edit contrat',
            'delete contrat',
            
            // Pour les factures
            'view factures',
            'create facture',
            'edit facture',
            'delete facture',
            
            // Pour les incidents
            'view incidents',
            'create incident',
            'edit incident',
            'delete incident',
            
            // Pour les catégories
            'view categories',
            'create categorie',
            'edit categorie',
            'delete categorie',
            
            // Pour les vendeurs
            'view vendeurs',
            'approve vendeur',
            'reject vendeur',
            'delete vendeur',
            
            // Pour les demandes
            'view demandes',
            'create demande',
            'edit demande',
            'delete demande',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(Permission::all());
        
        $role = Role::create(['name' => 'vendeur']);
        $role->givePermissionTo([
            'view formules', 'create formule', 'edit formule', 'delete formule',
            'view contrats', 'edit contrat',
            'view factures', 'create facture', 'edit facture',
            'view incidents', 'edit incident',
            'view categories',
            'view demandes', 'edit demande',
        ]);
        
        $role = Role::create(['name' => 'client']);
        $role->givePermissionTo([
            'view formules',
            'view contrats', 'create contrat',
            'view factures',
            'view incidents', 'create incident',
            'view categories',
            'create demande', 'view demandes',
        ]);
    }
}
