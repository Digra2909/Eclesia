<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['code_fidele', 'nom', 'postnom', 'prenom', 'date_naissance', 'telephone', 'grace', 'genre', 'path_qr_code', 'statut_id'])]
#[Hidden(['id'])]
class Fidele extends Model
{
    protected $casts = [
        'date_naissance' => 'date',
        'genre' => 'string',
        'path_qr_code' => 'string',
    ];

    public function statut(): BelongsTo
    {
        return $this->belongsTo(StatutFidele::class, 'statut_id');
    }

    public function nu(): HasOne
    {
        return $this->hasOne(Nu::class, 'fidele_id');
    }

    public function ouvriers(): HasMany
    {
        return $this->hasMany(Ouvrier::class, 'fidele_id');
    }

    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class, 'fidele_id');
    }

    public function interventions(): HasMany
    {
        return $this->hasMany(Intervention::class, 'fidele_id');
    }
}
