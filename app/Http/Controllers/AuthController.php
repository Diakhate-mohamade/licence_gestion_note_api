<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Méthode pour la connexion
    // public function login(Request $request)
    // {
    //     // Valider les données de la requête
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     // Récupérer les identifiants (email et mot de passe)
    //     $credentials = $request->only('email', 'password');

    //     // Vérifier si les identifiants sont valides
    //     if (Auth::attempt($credentials)) {
    //         // Récupérer l'utilisateur authentifié
    //         $user = Auth::user();

    //         // Créer un token pour l'utilisateur
    //         $token = $user->createToken('authToken')->plainTextToken;

    //         // Retourner la réponse avec les informations utilisateur et le token
    //         return response()->json([
    //             'user' => [
    //                 'id' => $user->id,
    //                 'nom' => $user->nom,
    //                 'prenom' => $user->prenom,
    //                 'email' => $user->email,
    //                 'role' => $user->role,  // Assurez-vous que le champ 'role' existe
    //                 'created_at' => $user->created_at,
    //                 'updated_at' => $user->updated_at,
    //             ],
    //             'token' => $token,
    //             'token_type' => 'Bearer',
    //         ], 200);
    //     }

    //     // Si les identifiants sont incorrects
    //     return response()->json(['message' => 'Identifiants incorrects'], 401);
    // }

    public function login(Request $request)
    {
        // Valider les données de la requête
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Récupérer les identifiants (email et mot de passe)
        $credentials = $request->only('email', 'password');

        // Vérifier si les identifiants sont valides
        if (Auth::attempt($credentials)) {
            // Récupérer l'utilisateur authentifié
            $user = Auth::user();

            // Créer un token pour l'utilisateur
            $token = $user->createToken('authToken')->plainTextToken;

            // Récupérer le rôle de l'utilisateur
            $role = $user->getRoleNames()->first(); // Cela renvoie le premier rôle de l'utilisateur

            // Retourner la réponse avec les informations utilisateur et le token
            return response()->json([
                'user' => [
                    'id' => $user->id,
                    'nom' => $user->nom,
                    'prenom' => $user->prenom,
                    'email' => $user->email,
                    'role' => $role, // Récupérer le rôle ici
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ],
                'token' => $token,
                'token_type' => 'Bearer',
            ], 200);
        }

        // Si les identifiants sont incorrects
        return response()->json(['message' => 'Identifiants incorrects'], 401);
    }

    // Méthode pour la déconnexion
    public function logout(Request $request)
    {
        // Supprimer les tokens de l'utilisateur (déconnexion)
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Déconnexion réussie']);
    }
}
