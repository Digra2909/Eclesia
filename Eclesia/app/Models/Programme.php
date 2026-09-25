<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['montant', 'statut', 'commentaire'])]
#[Hidden(['id'])]
class Programme extends Model
{
    protected $casts = [
        'montant' => 'integer',
        'statut' => 'string',
        'commentaire' => 'string',
    ];

    public function seances(): HasMany
    {
        return $this->hasMany(Seance::class, 'programme_id');
    }

    public function formationNus(): HasMany
    {
        return $this->hasMany(FormationNu::class, 'programme_id');
    }

    public function formationOrds(): HasMany
    {
        return $this->hasMany(FormationOrd::class, 'programme_id');
    }

    public function cours(): BelongsToMany
    {
        return $this->belongsToMany(Cours::class, 'cours_programme')->using(CoursProgramme::class);
    }
}
