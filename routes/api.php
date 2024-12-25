<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EtudiantController;
use App\Http\Controllers\EnseignantController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\MatiereController; // Ajoutez ceci
use Illuminate\Http\Request;

// Routes publiques
Route::post('/login', [AuthController::class, 'login']); // Authentification

// Routes protégées (authentification via Sanctum)
Route::middleware(['auth:sanctum'])->group(function () {
    // Nouvelle route pour obtenir les informations de l'utilisateur
    Route::get('/user', function (Request $request) {
        return $request->user(); // Retourne l'utilisateur authentifié
    });

    // Déconnexion
    Route::post('/logout', [AuthController::class, 'logout']); // Déconnexion

    // Routes admin (middleware 'role:admin')
    Route::middleware(['role:admin'])->group(function () {
        Route::post('/users', [AdminController::class, 'createUser']);
        Route::get('/users/{role}', [AdminController::class, 'listUsers']);
        Route::delete('/users/{id}', [AdminController::class, 'deleteUser']);
        Route::put('/users/{id}', [AdminController::class, 'updateUser']);

        // Gestion des classes
        Route::post('/classes', [ClasseController::class, 'createClasse']);
        Route::put('/classes/{id}', [ClasseController::class, 'updateClasse']);
        Route::get('/classes', [ClasseController::class, 'listClasse']);
        Route::delete('/classes/{id}', [ClasseController::class, 'deleteClasse']);

        // Gestion des matières
        Route::apiResource('matieres', MatiereController::class); // Routes pour les matières

        // Route::get('/matieres', [MatiereController::class, ' listMatieres']);


         // Route pour assigner une matière à une classe
         Route::post('matieres/{matiereId}/assign', [MatiereController::class, 'assignMatiereToClasse']);

          // Route pour assigner un enseignant à une classe et une matière
        Route::post('/assign-enseignant', [AdminController::class, 'assignEnseignantToClasseAndMatiere']);

        // Gerer Bulletin
        Route::get('/bulletins', [AdminController::class, 'Gererbulletins']);
    });

    // Routes étudiant (middleware 'role:etudiant')
    Route::middleware(['role:etudiant'])->group(function () {
        Route::get('/mes-notes', [EtudiantController::class, 'mesNotes']);
        Route::get('/mes-bulletins', [EtudiantController::class, 'mesBulletins']);
    });

    // Routes tuteur (middleware 'role:tuteur')
    Route::middleware(['role:tuteur'])->group(function () {
        Route::get('/notes-enfant/{etudiantId}', [EtudiantController::class, 'notesEnfant']);
        Route::get('/bulletins-enfant/{etudiantId}', [EtudiantController::class, 'bulletinsEnfant']);
        Route::get('/enfants', [EtudiantController::class, 'enfants']); // Ajoutez cette ligne
    });

    // Routes enseignant (middleware 'role:enseignant')
    Route::middleware(['role:enseignant'])->group(function () {
        Route::post('/noter-mcc', [EnseignantController::class, 'saisirNoteMcc']);
        Route::post('/noter-examen', [EnseignantController::class, 'saisirNoteExamen']);
        Route::get('/mes-etudiants', [EnseignantController::class, 'listEtudiants']);
        Route::get('/bulletins-etudiants', [EnseignantController::class, 'bulletinsEtudiants']);

        Route::put('/modifier-note-mcc/{id}', [EnseignantController::class, 'modifierNoteMcc']);
        Route::put('/modifier-note-examen/{id}', [EnseignantController::class, 'modifierNoteExamen']);

        Route::get('/mes-classes', [EnseignantController::class, 'listClasses']);

        // Route::get('/liste-mes-etudiant', [EnseignantController::class, 'listEtudiantsByClasse']);
        Route::get('/classe/{classeId}/etudiants', [EnseignantController::class, 'listEtudiantsByClasse']);


    });
});
