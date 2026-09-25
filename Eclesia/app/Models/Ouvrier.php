<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['code_ouvrier', 'poste_id', 'fidele_id', 'path_photo'])]
#[Hidden(['id'])]
class Ouvrier extends Model
{
    protected $casts = [
        'path_photo' => 'string',
    ];

    public function fidele(): BelongsTo
    {
        return $this->belongsTo(Fidele::class, 'fidele_id');
    }

    public function poste(): BelongsTo
    {
        return $this->belongsTo(Poste::class, 'poste_id');
    }
}
