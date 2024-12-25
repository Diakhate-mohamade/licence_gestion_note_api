<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Etudiant;
use App\Models\Tuteur;
use App\Models\Enseignant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    // Méthode pour créer un utilisateur (admin, enseignant, tuteur, étudiant)
    public function createUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'role' => 'required|in:admin,enseignant,tuteur,etudiant',
            'password' => 'required|min:8',
            'classe_id' => 'required_if:role,etudiant|exists:classes,id',
            'tuteur_id' => 'nullable|exists:tuteurs,id',
            'specialite' => 'required_if:role,enseignant|string|max:255',
            'profession' => 'required_if:role,tuteur|string|max:255',
            'matricule' => 'required_if:role,etudiant|string|max:50|unique:etudiants,matricule',
            'sexe' => 'required_if:role,etudiant|in:masculin,féminin',
            'lieu_naissance' => 'required_if:role,etudiant|string|max:255',
            'date_naissance' => 'required_if:role,etudiant|date',
            'adresse' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Création de l'utilisateur de base
        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assigner le rôle à l'utilisateur
        $user->assignRole($request->role);

        // Création des détails spécifiques en fonction du rôle
        switch ($request->role) {
            case 'etudiant':
                Etudiant::create([
                    'user_id' => $user->id,
                    'classe_id' => $request->classe_id,
                    'tuteur_id' => $request->tuteur_id,
                    'matricule' => $request->matricule,
                    'sexe' => $request->sexe,
                    'lieu_naissance' => $request->lieu_naissance,
                    'date_naissance' => $request->date_naissance,
                    'adresse' => $request->adresse,
                ]);
                break;

            case 'enseignant':
                Enseignant::create([
                    'user_id' => $user->id,
                    'specialite' => $request->specialite,
                    'adresse' => $request->adresse,
                ]);
                break;

            case 'tuteur':
                Tuteur::create([
                    'user_id' => $user->id,
                    'profession' => $request->profession,
                ]);
                break;
        }

        return response()->json([
            'message' => 'Utilisateur créé avec succès',
            'user' => $user,
        ], 201);
    }

    // Méthode pour lister les utilisateurs par rôle
    // public function listUsers($role)
    // {
    //     // Vérifier si le rôle existe
    //     if (!in_array($role, ['admin', 'enseignant', 'tuteur', 'etudiant'])) {
    //         return response()->json(['error' => 'Rôle invalide'], 400);
    //     }

    //     $users = User::role($role)->with($role)->get();

    //     return response()->json([
    //         'message' => "Liste des utilisateurs avec le rôle: $role",
    //         'users' => $users,
    //     ]);
    // }
    public function listUsers($role)
    {
        if (!in_array($role, ['admin', 'enseignant', 'tuteur', 'etudiant'])) {
            return response()->json(['error' => 'Rôle invalide'], 400);
        }

        // Pour admin, n'ayez pas de relation avec 'with', car il n'y a pas de modèle associé
        if ($role === 'admin') {
            $users = User::role('admin')->get();
        } else {
            // Charger les relations pour d'autres rôles
            $relation = $role == 'etudiant' ? 'etudiant' : ($role == 'enseignant' ? 'enseignant' : 'tuteur');
            $users = User::role($role)->with($relation)->get();
        }

        return response()->json([
            'message' => "Liste des utilisateurs avec le rôle: $role",
            'users' => $users,
        ]);
    }

    // Méthode pour supprimer un utilisateur
    public function deleteUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Utilisateur introuvable'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'Utilisateur supprimé avec succès']);
    }

    // Méthode pour mettre à jour un utilisateur
    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Utilisateur introuvable'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nom' => 'string|max:255',
            'prenom' => 'string|max:255',
            'email' => 'email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user->update([
            'nom' => $request->nom ?? $user->nom,
            'prenom' => $request->prenom ?? $user->prenom,
            'email' => $request->email ?? $user->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        return response()->json(['message' => 'Utilisateur mis à jour avec succès', 'user' => $user]);
    }

    public function assignEnseignantToClasseAndMatiere(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'enseignant_id' => 'required|exists:enseignants,id',
            'classe_id' => 'required|exists:classes,id',
            'matiere_id' => 'required|exists:matieres,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Assigner l'enseignant à la classe
        $enseignant = Enseignant::find($request->enseignant_id);
        $enseignant->classes()->attach($request->classe_id);

        // Assigner l'enseignant à la matière
        $enseignant->matieres()->attach($request->matiere_id);

        return response()->json([
            'message' => 'Enseignant assigné à la classe et à la matière avec succès',
            'enseignant' => $enseignant
        ], 201);
    }


    // Gestion bulletin
    public function Gererbulletins()
    {
        $enseignant = Auth::user()->enseignant;
        $classes = $enseignant->classes;

        // Récupérer les bulletins avec les UEs
        $bulletins = Bulletin::whereHas('etudiant', function($query) use ($classes) {
            $query->whereIn('classe_id', $classes->pluck('id'));
        })->with(['etudiant', 'ue'])->get();

        if ($bulletins->isEmpty()) {
            return response()->json(['message' => 'Aucun bulletin trouvé pour cet enseignant.'], 404);
        }

        foreach ($bulletins as $bulletin) {
            $ue = $bulletin->ue;

            // Récupérer les notes MCC et d'examen pour l'UE
            $noteMcc = NoteMcc::where('etudiant_id', $bulletin->etudiant_id)
                            ->where('id_matiere', $ue->id_note_mcc)
                            ->first();

            $noteExamen = NoteExamen::where('etudiant_id', $bulletin->etudiant_id)
                                    ->where('id_matiere', $ue->id_note_examen)
                                    ->first();

            // Initialiser le tableau pour les notes
            $notes = [];

            // Ajouter les notes si elles existent
            if ($noteMcc) {
                $notes[] = $noteMcc->note;
            }

            if ($noteExamen) {
                $notes[] = $noteExamen->note;
            }

            // Calcul de la moyenne
            if (count($notes) > 0) {
                $bulletin->moyenne_ue = array_sum($notes) / count($notes);
            } else {
                $bulletin->moyenne_ue = null; // ou 0 selon votre besoin
            }

            // Appréciation basée sur la moyenne
            $bulletin->appreciation_general = $this->getAppreciation($bulletin->moyenne_ue);
        }

        return response()->json($bulletins);
    }

    // Méthode pour obtenir l'appréciation
    private function getAppreciation($moyenne)
    {
        if ($moyenne === null) {
            return 'Aucune note disponible';
        }

        if ($moyenne >= 16) {
            return 'Très bien';
        } elseif ($moyenne >= 14) {
            return 'Bien';
        } elseif ($moyenne >= 12) {
            return 'Assez bien';
        } elseif ($moyenne >= 10) {
            return 'Passable';
        } else {
            return 'Insuffisant';
        }
    }
}
