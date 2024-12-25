<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

// class Matiere extends Model
// {
//     protected $fillable = ['nom', 'code'];

//     /**
//      * Relation : une matière appartient à une classe.
//      */
//     public function classe()
//     {
//         return $this->belongsTo(Classe::class);
//     }

//     /**
//      * Relation : une matière a un coefficient (un seul par classe).
//      */
//     public function coefficient()
//     {
//         return $this->hasOne(CoefficientEc::class);
//     }

//     /**
//      * Relation : une matière a plusieurs enseignants (via pivot table).
//      */
//     public function enseignants()
//     {
//         return $this->belongsToMany(Enseignant::class, 'enseignant_matiere');
//     }
// }


class Matiere extends Model
{
    protected $fillable = ['nom', 'code'];

    // Relation avec les classes
    public function coefficientsEc(): HasMany
    {
        return $this->hasMany(CoefficientEc::class);
    }
     // Définition de la relation avec les CoefficientEC
     public function coefficients()
     {
         return $this->hasMany(CoefficientEC::class); // Assurez-vous que ce modèle existe
     }

    // Relation avec les enseignants
    public function enseignants(): BelongsToMany
    {
        return $this->belongsToMany(Enseignant::class, 'enseignant_matiere');
    }

    public function noteMccs(): HasMany
    {
        return $this->hasMany(NoteMcc::class);
    }

    public function noteExamens(): HasMany
    {
        return $this->hasMany(NoteExamen::class);
    }
}
