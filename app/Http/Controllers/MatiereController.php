<?php
namespace App\Http\Controllers;

use App\Models\Matiere;
use App\Models\Classe;
use App\Models\CoefficientEC;
use Illuminate\Http\Request;

class MatiereController extends Controller
{
    // Récupère toutes les matières avec leurs coefficients et classes
    public function index()
    {
        return Matiere::with('coefficients.classe')->get();
    }


    // Crée une nouvelle matière après validation des données
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'nom' => 'required|string|max:255', // Vérifie que 'nom' est requis et est une chaîne de caractères
    //         'code' => 'required|string|unique:matieres,code|max:255', // Vérifie que 'code' est requis, unique et est une chaîne
    //     ]);

    //     // Crée la matière et retourne la réponse en JSON
    //     $matiere = Matiere::create($request->all());
    //     return response()->json($matiere, 201); // Retourne un code de statut 201 pour la création réussie
    // }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'code' => 'required|string|unique:matieres,code|max:255',
            'classe_id' => 'required|exists:classes,id', // Validation pour classe_id
            'coefficient' => 'required|numeric', // Validation pour coefficient

        ]);

        // Crée la matière
        $matiere = Matiere::create($request->only(['nom', 'code']));

        // Assigne la matière à la classe avec le coefficient
        CoefficientEC::create([
            'classe_id' => $request->classe_id,
            'matiere_id' => $matiere->id,
            'coefficient' => $request->coefficient
        ]);

        return response()->json($matiere, 201);
    }

    // Récupère une matière spécifique par ID
    public function show($id)
    {
        return Matiere::with('coefficients.classe')->findOrFail($id); // Trouve la matière ou échoue si non trouvée
    }

    // Met à jour une matière existante après validation des données
    public function update(Request $request, $id)
    {
        $request->validate([
            'nom' => 'required|string|max:255', // Vérifie que 'nom' est requis et est une chaîne
            'code' => 'required|string|max:255|unique:matieres,code,' . $id, // Vérifie que 'code' est unique sauf pour l'ID actuel
        ]);

        $matiere = Matiere::findOrFail($id); // Trouve la matière ou échoue
        $matiere->update($request->all()); // Met à jour la matière avec les nouvelles données
        return response()->json($matiere); // Retourne la matière mise à jour
    }

    // Supprime une matière par ID
    public function destroy($id)
    {
        $matiere = Matiere::findOrFail($id); // Trouve la matière ou échoue
        $matiere->delete(); // Supprime la matière
        return response()->json(null, 204); // Retourne un code de statut 204 pour une suppression réussie
    }

    // Assigne une matière à une classe avec un coefficient
    public function assignMatiereToClasse(Request $request, $matiereId)
    {
        $request->validate([
            'classe_id' => 'required|exists:classes,id', // Vérifie que 'classe_id' existe dans la table 'classes'
            'coefficient' => 'required|numeric', // Vérifie que 'coefficient' est requis et est un nombre
        ]);

        $coefficient = CoefficientEC::create([
            'classe_id' => $request->classe_id,
            'matiere_id' => $matiereId,
            'coefficient' => $request->coefficient,
        ]);

        return response()->json($coefficient, 201); // Retourne le coefficient créé avec un statut 201
    }

}
