<?php

namespace Tests\Feature;

use App\Models\Programme;
use Database\Factories\ProgrammeFactory;
use Database\Factories\SeanceFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeanceBatchTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Un lot de séances (le formulaire répété) est enregistré en une seule
     * transaction, rattaché au bon programme, avec un numero_seance donné.
     */
    public function test_store_batch_creates_all_seances_for_programme(): void
    {
        $programme = ProgrammeFactory::new()->create();

        $reponse = $this->post(route('seances.batch'), [
            'programme_id' => $programme->id,
            'seances' => [
                [
                    'numero_seance' => 1,
                    'date_seance' => '2026-09-01',
                    'heure_debut' => '09:00',
                    'heure_fin' => '10:00',
                    'lieu' => 'Salle A',
                ],
                [
                    'numero_seance' => 2,
                    'date_seance' => '2026-09-08',
                    'heure_debut' => '09:00',
                    'heure_fin' => '10:00',
                    'lieu' => 'Salle A',
                ],
            ],
        ]);

        $reponse->assertSessionHas('success');
        $this->assertDatabaseCount('seances', 2);
        $this->assertSame(
            [1, 2],
            $programme->seances->pluck('numero_seance')->sort()->values()->all()
        );
    }

    /**
     * L'heure de fin ne peut pas être antérieure (ni égale) à l'heure de début.
     */
    public function test_heure_fin_must_be_after_heure_debut(): void
    {
        $programme = ProgrammeFactory::new()->create();

        $this->post(route('seances.batch'), [
            'programme_id' => $programme->id,
            'seances' => [
                [
                    'numero_seance' => 1,
                    'date_seance' => '2026-09-01',
                    'heure_debut' => '10:00',
                    'heure_fin' => '09:30',
                ],
            ],
        ])->assertSessionHasErrors('seances.0.heure_fin');

        $this->assertDatabaseCount('seances', 0);
    }

    /**
     * Une séance ne peut être datée avant la séance qui la précède.
     */
    public function test_dates_must_be_in_ascending_order(): void
    {
        $programme = ProgrammeFactory::new()->create();

        $this->post(route('seances.batch'), [
            'programme_id' => $programme->id,
            'seances' => [
                [
                    'numero_seance' => 1,
                    'date_seance' => '2026-09-08',
                ],
                [
                    'numero_seance' => 2,
                    'date_seance' => '2026-09-01',
                ],
            ],
        ])->assertSessionHasErrors('seances.1.date_seance');

        $this->assertDatabaseCount('seances', 0);
    }

    /**
     * Les numéros continuent après la dernière séance existante du programme.
     */
    public function test_first_numero_must_follow_existing_seances(): void
    {
        $programme = ProgrammeFactory::new()->create();
        SeanceFactory::new()->create(['programme_id' => $programme->id, 'numero_seance' => 1]);

        $this->post(route('seances.batch'), [
            'programme_id' => $programme->id,
            'seances' => [
                ['numero_seance' => 1, 'date_seance' => '2026-09-08'],
            ],
        ])->assertSessionHasErrors('seances');

        $this->assertDatabaseCount('seances', 1);
    }

    /**
     * Le programme reste obligatoire au niveau racine du formulaire.
     */
    public function test_programme_id_is_required(): void
    {
        $this->post(route('seances.batch'), [
            'seances' => [
                ['numero_seance' => 1, 'date_seance' => '2026-09-08'],
            ],
        ])->assertSessionHasErrors('programme_id');

        $this->assertDatabaseCount('seances', 0);
    }
}
