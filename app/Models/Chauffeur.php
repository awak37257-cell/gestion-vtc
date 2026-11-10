<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Chauffeur extends Model
{
    protected $table = 'chauffeurs';
    protected $guarded = ['id'];

    public function candidature(): HasOne
    {
        return $this->hasOne(Candidature::class, 'chauffeur_id');
    }
}