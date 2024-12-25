<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tuteur extends Model
{
    protected $fillable = ['user_id', 'profession'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function etudiants(): HasMany
    {
        return $this->hasMany(Etudiant::class);
    }
}
