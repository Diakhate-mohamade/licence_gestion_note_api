<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

// class Etudiant extends Model
// {
//     protected $fillable = [
//         'user_id', 'classe_id', 'tuteur_id', 'matricule', 'sexe',
//         'lieu_naissance', 'date_naissance', 'adresse',
//     ];

//     public function user()
//     {
//         return $this->belongsTo(User::class);
//     }

//     public function classe()
//     {
//         return $this->belongsTo(Classe::class);
//     }

//     public function tuteur()
//     {
//         return $this->belongsTo(Tuteur::class);
//     }

//     public function notesMcc()
//     {
//         return $this->hasMany(NoteMcc::class);
//     }

//     public function notesExamen()
//     {
//         return $this->hasMany(NoteExamen::class);
//     }
// }


class Etudiant extends Model
{
    protected $fillable = [
        'user_id', 'classe_id', 'tuteur_id', 'matricule',
        'sexe', 'lieu_naissance', 'date_naissance', 'adresse'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }

    public function tuteur(): BelongsTo
    {
        return $this->belongsTo(Tuteur::class);
    }

    public function bulletins(): HasMany
    {
        return $this->hasMany(Bulletin::class);
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
