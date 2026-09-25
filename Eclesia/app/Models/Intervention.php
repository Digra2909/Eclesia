<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['type_intervention_id', 'fidele_id', 'seance_id'])]
#[Hidden(['id'])]
class Intervention extends Model
{
    public function typeIntervention(): BelongsTo
    {
        return $this->belongsTo(TypeIntervention::class, 'type_intervention_id');
    }

    public function fidele(): BelongsTo
    {
        return $this->belongsTo(Fidele::class, 'fidele_id');
    }

    public function seance(): BelongsTo
    {
        return $this->belongsTo(Seance::class, 'seance_id');
    }
}
