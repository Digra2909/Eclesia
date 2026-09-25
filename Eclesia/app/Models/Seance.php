<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['numero_seance', 'date_seance', 'heure_debut', 'heure_fin', 'lieu', 'delai_rappel', 'programme_id'])]
#[Hidden(['id'])]
class Seance extends Model
{
    protected $casts = [
        'heure_debut' => 'datetime:H:i',
        'heure_fin' => 'datetime:H:i',
    ];

    public function programme(): BelongsTo
    {
        return $this->belongsTo(Programme::class, 'programme_id');
    }

    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class, 'seance_id');
    }

    public function interventions(): HasMany
    {
        return $this->hasMany(Intervention::class, 'seance_id');
    }
}
