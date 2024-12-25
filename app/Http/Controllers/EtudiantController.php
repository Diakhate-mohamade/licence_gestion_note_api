<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use App\Models\NoteMcc;
use App\Models\NoteExamen;
use App\Models\Bulletin;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class EtudiantController extends Controller
{
    // Notes de l'étudiant connecté
    public function mesNotes()
    {
        $etudiant = Auth::user()->etudiant;

        $notes = [
            'notes_mcc' => NoteMcc::where('etudiant_id', $etudiant->id)->with('matiere')->get(),
            'notes_examen' => NoteExamen::where('etudiant_id', $etudiant->id)->with('matiere')->get()
        ];

        return response()->json($notes);
    }

    // Bulletins de l'étudiant connecté
    public function mesBulletins()
    {
        $etudiant = Auth::user()->etudiant;

        $bulletins = Bulletin::where('etudiant_id', $etudiant->id)->with('ue')->get();

        return response()->json($bulletins);
    }

    // public function enfants()
    // {
    //     $tuteur = Auth::user()->tuteur;

    //     // Récupérer les enfants associés au tuteur
    //     $enfants = Etudiant::where('tuteur_id', $tuteur->id)->get();

    //     return response()->json($enfants);
    // }

    public function enfants()
    {
        $tuteur = Auth::user()->tuteur;

        // Récupérer les enfants associés au tuteur avec les informations de l'utilisateur
        $enfants = Etudiant::with('user')->where('tuteur_id', $tuteur->id)->get();

        return response()->json($enfants);
    }

    // Notes accessibles par le tuteur/parent
    public function notesEnfant($etudiantId)
    {
        $tuteur = Auth::user()->tuteur;

        $etudiant = Etudiant::where('tuteur_id', $tuteur->id)->findOrFail($etudiantId);

        $notes = [
            'notes_mcc' => NoteMcc::where('etudiant_id', $etudiant->id)->with('matiere')->get(),
            'notes_examen' => NoteExamen::where('etudiant_id', $etudiant->id)->with('matiere')->get()
        ];

        return response()->json($notes);
    }

    public function bulletinsEnfant($etudiantId)
    {
        $tuteur = Auth::user()->tuteur;

        $etudiant = Etudiant::where('tuteur_id', $tuteur->id)->findOrFail($etudiantId);

        $bulletins = Bulletin::where('etudiant_id', $etudiant->id)->with('ue')->get();

        return response()->json($bulletins);
    }
}
