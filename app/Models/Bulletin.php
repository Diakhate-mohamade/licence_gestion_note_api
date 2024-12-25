<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Bulletin extends Model
{
    protected $fillable = [
        'etudiant_id', 'ue_id', 'annee_scolaire',
        'semestre', 'credit_total',
        'appreciation_general', 'decision'
    ];

    public function etudiant(): BelongsTo
    {
        return $this->belongsTo(Etudiant::class);
    }

    public function ue(): BelongsTo
    {
        return $this->belongsTo(UE::class);
    }
}
