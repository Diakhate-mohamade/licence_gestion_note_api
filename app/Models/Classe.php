<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

// class Classe extends Model
// {
//     protected $fillable = ['nom', 'niveau'];

//     /**
//      * Relation : une classe a plusieurs matières.
//      */
//     public function matieres()
//     {
//         return $this->hasMany(Matiere::class);
//     }

//     /**
//      * Relation : une classe a plusieurs coefficients (un par matière).
//      */
//     public function coefficients()
//     {
//         return $this->hasMany(CoefficientEc::class);
//     }

//     /**
//      * Relation : une classe a plusieurs étudiants.
//      */
//     public function etudiants()
//     {
//         return $this->hasMany(Etudiant::class);
//     }

//     /**
//      * Relation : une classe a plusieurs enseignants (via pivot table).
//      */
//     public function enseignants()
//     {
//         return $this->belongsToMany(Enseignant::class, 'classe_enseignant');
//     }
// }


class Classe extends Model
{
    protected $fillable = ['nom', 'niveau'];

    public function etudiants(): HasMany
    {
        return $this->hasMany(Etudiant::class);
    }

    public function enseignants(): BelongsToMany
    {
        return $this->belongsToMany(Enseignant::class, 'classe_enseignant');
    }

    // Relation avec les coefficients
    public function coefficientsEc(): HasMany
    {
        return $this->hasMany(CoefficientEc::class);
    }

    // Relation avec les matières
    public function matieres()
    {
        return $this->belongsToMany(Matiere::class, 'coefficients_ec');
    }
}
