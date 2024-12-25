<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crée un utilisateur avec le rôle 'admin'
        User::create([
            'nom' => 'Admin',
            'prenom' => 'Super',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'), // Assurez-vous que le mot de passe est haché
            'role' => 'admin', // ou le rôle de votre choix
        ]);
    }
}
