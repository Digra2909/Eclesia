<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['code_nu', 'note_oral', 'note_ecrite', 'pourcentage', 'statut', 'fidele_id'])]
#[Hidden(['id'])]
class Nu extends Model
{
    protected $casts = [
        'note_oral' => 'decimal:1',
        'note_ecrite' => 'decimal:1',
        'pourcentage' => 'decimal:1',
    ];

    public function fidele(): BelongsTo
    {
        return $this->belongsTo(Fidele::class, 'fidele_id');
    }
}
