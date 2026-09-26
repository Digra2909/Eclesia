<?php

namespace Tests\Feature;

use App\Models\Cours;
use App\Models\Programme;
use Database\Factories\ProgrammeFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProgrammeCoursTest extends TestCase
{
    use RefreshDatabase;

    /**
     * L'étape « associer des cours » rattache des cours existants au programme
     * et bascule vers l'ajout des séances.
     */
    public function test_curs_sync_attaches_and_forwards_to_seances(): void
    {
        $programme = ProgrammeFactory::new()->create();
        $coursA = Cours::create(['passages' => 'Jean 3:16']);
        $coursB = Cours::create(['passages' => 'Actes 2:38']);

        $reponse = $this->post(route('programmes.cours', $programme), [
            'cours_ids' => [$coursA->id, $coursB->id],
        ]);

        $reponse->assertRedirect(route('dashboard'));
        $reponse->assertSessionHas('seances_pour_programme', $programme->id);
        $this->assertSame(
            [$coursA->id, $coursB->id],
            $programme->cours->pluck('id')->sort()->values()->all()
        );
    }

    /**
     * L'ajout rapide (JSON) crée le cours ET l'attache, sans écraser les cours
     * déjà associés au programme.
     */
    public function test_quick_add_creates_and_attaches_without_wiping(): void
    {
        $programme = ProgrammeFactory::new()->create();
        $coursExistant = Cours::create(['passages' => 'Jean 3:16']);
        $programme->cours()->sync([$coursExistant->id]);

        $reponse = $this->postJson(route('programmes.cours', $programme), [
            'nouveau_passages' => 'Matthieu 5:9',
        ]);

        $nouveau = $reponse->json('cours');
        $this->assertNotEquals($coursExistant->id, $nouveau['id'] ?? null);
        $this->assertSame('Matthieu 5:9', $nouveau['passages']);
        $this->assertSame(
            [$coursExistant->id, $nouveau['id']],
            $programme->cours->pluck('id')->sort()->values()->all()
        );
    }
}
