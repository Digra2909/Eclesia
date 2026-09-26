<?php

namespace Database\Factories;

use App\Models\Seance;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Seance>
 */
class SeanceFactory extends Factory
{
    protected $model = Seance::class;

    public function definition(): array
    {
        return [
            'programme_id' => ProgrammeFactory::new(),
            'numero_seance' => 1,
            'date_seance' => fake()->date(),
            'heure_debut' => '09:00',
            'heure_fin' => '10:00',
            'lieu' => 'Temple de l\'église',
            'delai_rappel' => null,
        ];
    }
}
