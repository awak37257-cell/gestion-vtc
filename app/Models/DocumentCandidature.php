<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentCandidature extends Model
{
    protected $table = 'documents_candidature';
    protected $guarded = ['id'];
    public $timestamps = false; // Puisqu'on utilise cree_le

    protected $casts = [
        'est_valide' => 'boolean',
        'renvoye_le' => 'datetime',
    ];

    public const LIBELLES = [
        'permis_recto' => 'Permis de conduire · recto',
        'permis_verso' => 'Permis de conduire · verso',
        'carte_vtc_recto' => 'Carte VTC · recto',
        'carte_vtc_verso' => 'Carte VTC · verso',
        'piece_identite' => 'Pièce d’identité',
    ];

    // Nom lisible du document (ex. « Permis de conduire · recto »)
    public function libelle(): string
    {
        return self::LIBELLES[$this->type_document] ?? ucfirst(str_replace('_', ' ', $this->type_document));
    }

    public function candidature(): BelongsTo
    {
        return $this->belongsTo(Candidature::class, 'candidature_id');
    }
}