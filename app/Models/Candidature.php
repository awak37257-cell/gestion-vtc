<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Candidature extends Model
{
    protected function casts(): array
{
    return [
        'jeton_correction_expire_le' => 'datetime', // Remplacez par le nom exact de votre colonne
    ];
}
    protected $table = 'candidatures';
    protected $guarded = ['id'];

    public function chauffeur(): BelongsTo
    {
        return $this->belongsTo(Chauffeur::class, 'chauffeur_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(DocumentCandidature::class, 'candidature_id');
    }

    public function entretiens(): HasMany
    {
        return $this->hasMany(Entretien::class, 'candidature_id');
    }
}