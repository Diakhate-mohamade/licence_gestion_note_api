<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


// class Enseignant extends Model
// {
//     protected $fillable = ['user_id', 'adresse', 'specialite'];

//     public function user()
//     {
//         return $this->belongsTo(User::class);
//     }

//     public function matieres()
//     {
//         return $this->belongsToMany(Matiere::class, 'enseignant_matiere');
//     }

//     public function classes()
//     {
//         return $this->belongsToMany(Classe::class, 'classe_enseignant');
//     }
// }

class Enseignant extends Model
{
    protected $fillable = ['user_id', 'adresse', 'specialite'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(Classe::class, 'classe_enseignant');
    }

    // Relation avec les matières
    public function matieres(): BelongsToMany
    {
        return $this->belongsToMany(Matiere::class, 'enseignant_matiere');
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
