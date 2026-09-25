<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['passages'])]
#[Hidden(['id'])]
class Cours extends Model
{
    public function programmes(): BelongsToMany
    {
        return $this->belongsToMany(Programme::class, 'cours_programme')->using(CoursProgramme::class);
    }
}
