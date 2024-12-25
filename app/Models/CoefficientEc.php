<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


// class CoefficientEc extends Model
// {

//     protected $table = 'coefficients_ec';

//     protected $fillable = ['classe_id', 'matiere_id', 'coefficient'];

//     /**
//      * Relation : un coefficient appartient à une classe.
//      */
//     public function classe()
//     {
//         return $this->belongsTo(Classe::class);
//     }

//     /**
//      * Relation : un coefficient appartient à une matière.
//      */
//     public function matiere()
//     {
//         return $this->belongsTo(Matiere::class);
//     }
// }

class CoefficientEc extends Model
{
    protected $table = 'coefficients_ec';
    protected $fillable = ['classe_id', 'matiere_id', 'coefficient'];

      // Relation avec la classe
    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }

    // Relation avec la matière
    public function matiere(): BelongsTo
    {
        return $this->belongsTo(Matiere::class);
    }

}
