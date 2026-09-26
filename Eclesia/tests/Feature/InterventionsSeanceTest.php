<?php

namespace Tests\Feature;

use App\Livewire\Seances;
use App\Models\Fidele;
use App\Models\Intervention;
use App\Models\Programme;
use App\Models\Seance;
use App\Models\TypeIntervention;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class InterventionsSeanceTest extends TestCase
{
    use RefreshDatabase;

    private function programme(): Programme
    {
        return Programme::create(['montant' => 8000, 'statut' => 'créé']);
    }

    private function fidele(string $code, string $prenom): Fidele
    {
        return Fidele::create([
            'code_fidele' => $code,
            'nom' => 'Doe',
            'postnom' => 'X',
            'prenom' => $prenom,
            'genre' => 'M',
        ]);
    }

    public function test_la_validation_des_seances_cree_les_interventions_des_2_lignes(): void
    {
        $programme = $this->programme();
        $fidele1 = $this->fidele('COMP-CNTRL-1', 'John');
        $fidele2 = $this->fidele('COMP-CNTRL-2', 'Jane');
        $type = TypeIntervention::create(['designation' => 'Pasteur']);

        Livewire::test(Seances::class)
            ->set('programmeId', $programme->id)
            ->set('lignes.0.date_seance', '2026-09-30')
            ->set('lignes.0.heure_debut', '09:00')
            ->set('lignes.0.intervention1_fidele', $fidele1->id)
            ->set('lignes.0.intervention1_type', $type->id)
            ->set('lignes.0.intervention2_fidele', $fidele2->id)
            ->set('lignes.0.intervention2_type', $type->id)
            ->call('enregistrer')
            ->assertHasNoErrors()
            ->assertSet('message', '1 séance(s) enregistrée(s) avec succès et 2 intervention(s).');

        $this->assertSame(1, Seance::count());
        $this->assertSame(2, Intervention::count());

        $this->assertDatabaseHas('interventions', [
            'fidele_id' => $fidele1->id,
            'type_intervention_id' => $type->id,
        ]);
        $this->assertDatabaseHas('interventions', [
            'fidele_id' => $fidele2->id,
            'type_intervention_id' => $type->id,
        ]);
    }

    public function test_les_lignes_d_interventions_incompletes_sont_ignorees(): void
    {
        $programme = $this->programme();
        $fidele = $this->fidele('COMP-CNTRL-1', 'John');
        $type = TypeIntervention::create(['designation' => 'Louange']);

        Livewire::test(Seances::class)
            ->set('programmeId', $programme->id)
            ->set('lignes.0.date_seance', '2026-09-30')
            ->set('lignes.0.heure_debut', '09:00')
            ->set('lignes.0.intervention1_fidele', $fidele->id)
            ->set('lignes.0.intervention2_type', $type->id)
            ->call('enregistrer')
            ->assertHasNoErrors();

        $this->assertSame(1, Seance::count());
        $this->assertSame(0, Intervention::count());
    }

    public function test_le_meme_fidele_et_type_dans_les_2_lignes_n_est_pas_duplique(): void
    {
        $programme = $this->programme();
        $fidele = $this->fidele('COMP-CNTRL-1', 'John');
        $type = TypeIntervention::create(['designation' => 'Pasteur']);

        Livewire::test(Seances::class)
            ->set('programmeId', $programme->id)
            ->set('lignes.0.date_seance', '2026-09-30')
            ->set('lignes.0.heure_debut', '09:00')
            ->set('lignes.0.intervention1_fidele', $fidele->id)
            ->set('lignes.0.intervention1_type', $type->id)
            ->set('lignes.0.intervention2_fidele', $fidele->id)
            ->set('lignes.0.intervention2_type', $type->id)
            ->call('enregistrer')
            ->assertHasNoErrors();

        $this->assertSame(1, Seance::count());
        $this->assertSame(1, Intervention::count());
    }

    public function test_malgres_les_interventions_la_seance_est_bien_creee(): void
    {
        $programme = $this->programme();

        Livewire::test(Seances::class)
            ->set('programmeId', $programme->id)
            ->set('lignes.0.date_seance', '2026-09-30')
            ->set('lignes.0.heure_debut', '09:00')
            ->call('enregistrer')
            ->assertHasNoErrors()
            ->assertSet('message', '1 séance(s) enregistrée(s) avec succès.');

        $this->assertSame(1, Seance::count());
        $this->assertSame(0, Intervention::count());
    }
}
