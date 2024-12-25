<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run()
    {
        // Créer les rôles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $enseignantRole = Role::firstOrCreate(['name' => 'enseignant']);
        $tuteurRole = Role::firstOrCreate(['name' => 'tuteur']);
        $etudiantRole = Role::firstOrCreate(['name' => 'etudiant']);

        // Créer des permissions pour l'API
        $permissions = [
            'voir dashboard',
            'gerer utilisateurs',
            'gerer etudiants',
            'gerer enseignants',
            'gerer classes',
            'gerer matieres',
            'gerer notes',
            'gerer bulletins',
            'consulter bulletins',
            'consulter notes'
        ];

        // Créer ou vérifier les permissions
        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        // Attribution des permissions par rôle
        $adminRole->syncPermissions([
            'voir dashboard',
            'gerer utilisateurs',
            'gerer etudiants',
            'gerer enseignants',
            'gerer classes',
            'gerer matieres',
            'gerer notes',
            'gerer bulletins',
            'consulter bulletins',
            'consulter notes'
        ]);

        $enseignantRole->syncPermissions([
            'voir dashboard',
            'gerer notes',
            'consulter notes',
        ]);

        $tuteurRole->syncPermissions([
            'voir dashboard',
            'consulter bulletins',
        ]);

        $etudiantRole->syncPermissions([
            'voir dashboard',
            'consulter bulletins',
            'consulter notes',
        ]);

        // Optionnel : Ajouter un rôle super-admin (si nécessaire)
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdminRole->syncPermissions(Permission::all());  // Super-admin a toutes les permissions
    }
}
