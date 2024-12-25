<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// class Ue extends Model
// {
//     use HasFactory;

//     protected $fillable = [
//         'credit', 'id_note_mcc', 'id_note_examen', 'moyenneCoef', 'moyenneUE', 'appreciation'
//     ];

//     public function bulletin()
//     {
//         return $this->hasOne(Bulletin::class);
//     }
// }


class UE extends Model
{
    protected $table = 'u_e_s';
    protected $fillable = [
        'credit', 'id_note_mcc', 'id_note_examen',
        'moyenneCoef', 'moyenneUE', 'appreciation'
    ];

    public function noteMcc(): BelongsTo
    {
        return $this->belongsTo(NoteMcc::class, 'id_note_mcc');
    }

    public function noteExamen(): BelongsTo
    {
        return $this->belongsTo(NoteExamen::class, 'id_note_examen');
    }
}
