<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['est_present', 'fidele_id', 'seance_id'])]
#[Hidden(['id'])]
class Presence extends Model
{
    public function fidele(): BelongsTo
    {
        return $this->belongsTo(Fidele::class, 'fidele_id');
    }

    public function seance(): BelongsTo
    {
        return $this->belongsTo(Seance::class, 'seance_id');
    }
}
