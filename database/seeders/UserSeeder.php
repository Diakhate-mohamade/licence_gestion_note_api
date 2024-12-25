<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Créer l'utilisateur admin
        $admin = User::create([
            'nom' => 'Admin',
            'prenom' => 'Super',
            'phone' => '77 453-58-58',
            'email' => 'admin@exemple.com',
            'password' => Hash::make('admin1234'),  // Utilisez un mot de passe sécurisé en production
        ]);

        // Récupérer le rôle admin
        $adminRole = Role::findByName('admin');

        // Assigner le rôle admin à l'utilisateur
        $admin->assignRole($adminRole);

        // Si vous voulez ajouter des permissions spécifiques à cet admin, vous pouvez le faire ici.
        // Par exemple, assigner toutes les permissions associées au rôle admin
        $admin->syncPermissions(Permission::all());
    }
}

