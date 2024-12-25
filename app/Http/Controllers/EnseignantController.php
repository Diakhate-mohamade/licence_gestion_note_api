<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use App\Models\NoteMcc;
use App\Models\NoteExamen;
use App\Models\Bulletin;
use App\Models\Classe;
use App\Models\Matiere;
use App\Models\CoefficientEc;
use App\Models\Coefficient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EnseignantController extends Controller
{
    // Saisie des notes MCC
    public function saisirNoteMcc(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'etudiant_id' => 'required|exists:etudiants,id',
            'matiere_id' => 'required|exists:matieres,id',
            'note' => 'required|numeric|between:0,20'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $enseignant = Auth::user()->enseignant;

        $note = NoteMcc::create([
            'etudiant_id' => $request->etudiant_id,
            'id_matiere' => $request->matiere_id,
            'enseignant_id' => $enseignant->id,
            'note' => $request->note
        ]);

        return response()->json([
            'message' => 'Note MCC saisie avec succès.',
            'note' => $note
        ], 201);
    }

    // Saisie des notes d'examen
    public function saisirNoteExamen(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'etudiant_id' => 'required|exists:etudiants,id',
            'matiere_id' => 'required|exists:matieres,id',
            'note' => 'required|numeric|between:0,20'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $enseignant = Auth::user()->enseignant;

        $note = NoteExamen::create([
            'etudiant_id' => $request->etudiant_id,
            'id_matiere' => $request->matiere_id,
            'enseignant_id' => $enseignant->id,
            'note' => $request->note
        ]);

        return response()->json([
            'message' => 'Note d\'examen saisie avec succès.',
            'note' => $note
        ], 201);
    }

    // Liste des classes enseignées par l'enseignant connecté
    public function listClasses()
    {
        // Récupérer l'enseignant connecté
        $enseignant = Auth::user()->enseignant;

        if (!$enseignant) {
            return response()->json(['message' => 'Enseignant non trouvé.'], 404);
        }

        // Récupérer les classes enseignées par cet enseignant
        $classes = $enseignant->classes()
            ->select('classes.id as classe_id', 'classes.nom', 'classes.niveau')
            ->get();

        return response()->json([
            'message' => 'Liste des classes récupérée avec succès.',
            'classes' => $classes,
        ]);
    }

    // Liste des étudiants d'une classe spécifique avec leurs notes
    // public function listEtudiantsByClasse($classeId)
    // {
    //     $enseignant = Auth::user()->enseignant;

    //     // Vérifiez si l'enseignant enseigne cette classe
    //     $classe = $enseignant->classes()->find($classeId);
    //     if (!$classe) {
    //         return response()->json(['message' => 'Classe non trouvée ou vous n\'avez pas accès à cette classe.'], 404);
    //     }

    //     // Récupérer les étudiants de la classe avec leurs notes
    //     $etudiants = Etudiant::where('classe_id', $classeId)
    //         ->with(['noteMccs', 'noteExamens']) // Charge les notes MCC et d'examen
    //         ->get();

    //     // Initialisez les notes à 0 si elles sont nulles
    //     foreach ($etudiants as $etudiant) {
    //         $etudiant->noteMCC = $etudiant->noteMccs->isNotEmpty() ? $etudiant->noteMccs->first()->note : 0;
    //         $etudiant->noteExamen = $etudiant->noteExamens->isNotEmpty() ? $etudiant->noteExamens->first()->note : 0;
    //     }

    //     return response()->json([
    //         'message' => 'Liste des étudiants récupérée avec succès.',
    //         'etudiants' => $etudiants,
    //     ]);
    // }

   // Liste des étudiants d'une classe spécifique avec leurs notes par matière enseignée
   public function listEtudiantsByClasse($classeId)
   {
       $enseignant = Auth::user()->enseignant;

       // Vérifiez si l'enseignant enseigne cette classe
       $classe = $enseignant->classes()->find($classeId);
       if (!$classe) {
           return response()->json(['message' => 'Classe non trouvée ou vous n\'avez pas accès à cette classe.'], 404);
       }

       // Récupérer les matières enseignées par l'enseignant dans cette classe
       $matieres = CoefficientEc::where('classe_id', $classeId)
           ->whereHas('matiere.enseignants', function ($query) use ($enseignant) {
               $query->where('enseignant_id', $enseignant->id);
           })
           ->pluck('matiere_id'); // Récupérer seulement les IDs des matières

       // Récupérer les étudiants de la classe
       $etudiants = Etudiant::where('classe_id', $classeId)
           ->with(['noteMccs.matiere', 'noteExamens.matiere']) // Inclure les matières dans les notes
           ->get();

       // Initialisez les notes à 0 si elles sont nulles
       foreach ($etudiants as $etudiant) {
           $etudiant->noteMCC = $etudiant->noteMccs->isNotEmpty() ? $etudiant->noteMccs->sum('note') / $etudiant->noteMccs->count() : 0;
           $etudiant->noteExamen = $etudiant->noteExamens->isNotEmpty() ? $etudiant->noteExamens->sum('note') / $etudiant->noteExamens->count() : 0;
       }

       return response()->json([
           'message' => 'Liste des étudiants récupérée avec succès.',
           'etudiants' => $etudiants,
       ]);
   }

  // Liste des étudiants de l'enseignant avec leurs notes
    public function listEtudiants()
    {
        $enseignant = Auth::user()->enseignant;
        $classes = $enseignant->classes;

        // Récupérer les étudiants avec leurs notes
        $etudiants = Etudiant::whereIn('classe_id', $classes->pluck('id'))
            ->with(['noteMccs', 'noteExamens']) // Charge les notes MCC et d'examen
            ->get();

        return response()->json($etudiants);
    }

    // Modifier une note MCC
    public function modifierNoteMcc(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'note' => 'required|numeric|between:0,20'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $noteMcc = NoteMcc::findOrFail($id);
        $noteMcc->note = $request->note;
        $noteMcc->save();

        return response()->json(['message' => 'Note MCC modifiée avec succès.']);
    }

    // Modifier une note d'examen
    public function modifierNoteExamen(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'note' => 'required|numeric|between:0,20'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $noteExamen = NoteExamen::findOrFail($id);
        $noteExamen->note = $request->note;
        $noteExamen->save();

        return response()->json(['message' => 'Note d\'examen modifiée avec succès.']);
    }

    // Bulletins des étudiants
    public function bulletinsEtudiants()
    {
        $enseignant = Auth::user()->enseignant;
        $classes = $enseignant->classes;

        $bulletins = Bulletin::whereHas('etudiant', function($query) use ($classes) {
            $query->whereIn('classe_id', $classes->pluck('id'));
        })->with('etudiant', 'ue')->get();

        return response()->json($bulletins);
    }

    // public function bulletinsEtudiants()
    // {
    //     $enseignant = Auth::user()->enseignant;
    //     $classes = $enseignant->classes;

    //     $bulletins = Bulletin::whereHas('etudiant', function($query) use ($classes) {
    //         $query->whereIn('classe_id', $classes->pluck('id'));
    //     })->with(['etudiant', 'ue'])->get();

    //     if ($bulletins->isEmpty()) {
    //         return response()->json(['message' => 'Aucun bulletin trouvé pour cet enseignant.'], 404);
    //     }

    //     foreach ($bulletins as $bulletin) {
    //         $ue = $bulletin->ue;

    //         // Récupérer les notes MCC et d'examen
    //         $noteMcc = NoteMcc::where('etudiant_id', $bulletin->etudiant_id)
    //                         ->where('id_matiere', $ue->id_note_mcc)
    //                         ->first();

    //         $noteExamen = NoteExamen::where('etudiant_id', $bulletin->etudiant_id)
    //                                 ->where('id_matiere', $ue->id_note_examen)
    //                                 ->first();

    //         // Calcul de la moyenne
    //         $notes = [];
    //         if ($noteMcc) {
    //             $notes[] = $noteMcc->note;
    //         }

    //         if ($noteExamen) {
    //             $notes[] = $noteExamen->note;
    //         }

    //         // Calcul de la moyenne
    //         if (count($notes) > 0) {
    //             $bulletin->moyenne = array_sum($notes) / count($notes);
    //         } else {
    //             $bulletin->moyenne = null; // ou 0 selon votre besoin
    //         }

    //         // Optionnel : Appréciation basée sur la moyenne
    //         $bulletin->appreciation_general = $this->getAppreciation($bulletin->moyenne);
    //     }

    //     return response()->json($bulletins);
    // }

    // // Méthode pour obtenir l'appréciation
    // private function getAppreciation($moyenne)
    // {
    //     if ($moyenne === null) {
    //         return 'Aucune note disponible';
    //     }

    //     if ($moyenne >= 16) {
    //         return 'Très bien';
    //     } elseif ($moyenne >= 14) {
    //         return 'Bien';
    //     } elseif ($moyenne >= 12) {
    //         return 'Assez bien';
    //     } elseif ($moyenne >= 10) {
    //         return 'Passable';
    //     } else {
    //         return 'Insuffisant';
    //     }
    // }
}
