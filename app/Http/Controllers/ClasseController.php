<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClasseController extends Controller
{
    // Méthode pour lister toutes les classes
    public function listClasse()
    {
        $classes = Classe::all();

        return response()->json([
            'message' => 'Liste des classes récupérée avec succès',
            'classes' => $classes,
        ], 200);
    }

    // Méthode pour ajouter une classe
    public function createClasse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255|:classes,nom',
            'niveau' => 'required|string|max:255', // Exemple : Primaire, Secondaire
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $classe = Classe::create([
            'nom' => $request->nom,
            'niveau' => $request->niveau,
        ]);

        return response()->json([
            'message' => 'Classe ajoutée avec succès',
            'classe' => $classe,
        ], 201);
    }

    // Méthode pour modifier une classe
    public function updateClasse(Request $request, $id)
    {
        $classe = Classe::find($id);

        if (!$classe) {
            return response()->json(['error' => 'Classe introuvable'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nom' => 'string|max:255|unique:classes,nom,' . $classe->id,
            'niveau' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $classe->update([
            'nom' => $request->nom ?? $classe->nom,
            'niveau' => $request->niveau ?? $classe->niveau,
        ]);

        return response()->json([
            'message' => 'Classe mise à jour avec succès',
            'classe' => $classe,
        ]);
    }

    // Méthode pour supprimer une classe
    public function deleteClasse($id)
    {
        $classe = Classe::find($id);

        if (!$classe) {
            return response()->json(['error' => 'Classe introuvable'], 404);
        }

        $classe->delete();

        return response()->json([
            'message' => 'Classe supprimée avec succès',
        ], 200);
    }
}
