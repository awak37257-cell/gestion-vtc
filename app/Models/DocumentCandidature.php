<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentCandidature extends Model
{
    protected $table = 'documents_candidature';
    protected $guarded = ['id'];
    public $timestamps = false; // Puisqu'on utilise cree_le

    public function candidature(): BelongsTo
    {
        return $this->belongsTo(Candidature::class, 'candidature_id');
    }
}