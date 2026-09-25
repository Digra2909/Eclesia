<?php

namespace Tests\Feature;

use App\Models\Fidele;
use App\Models\Presence;
use App\Models\Programme;
use App\Models\Seance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PresenceDefaultSeanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_presence_est_rattachee_a_la_seance_du_jour_sans_seance_id(): void
    {
        $programme = Programme::create(['montant' => 5000, 'statut' => 'créé']);
        $seance = Seance::create([
            'numero_seance' => 1,
            'date_seance' => today(),
            'heure_debut' => '08:00:00',
            'programme_id' => $programme->id,
        ]);
        $fidele = Fidele::create(['code_fidele' => 'CODE-JOUR-1', 'nom' => 'Doe', 'postnom' => 'X', 'prenom' => 'Jane', 'genre' => 'F', 'path_qr_code' => 'qr-1.png']);

        $reponse = $this->postJson(route('presences.store'), [
            'code_fidele' => $fidele->code_fidele,
        ]);

        $reponse->assertStatus(201);
        $this->assertSame($seance->id, $reponse->json('presence.seance_id'));
        $this->assertDatabaseHas('presences', [
            'fidele_id' => $fidele->id,
            'seance_id' => $seance->id,
            'est_present' => true,
        ]);
    }

    public function test_un_doublon_sur_la_meme_seance_du_jour_retourne_200(): void
    {
        $programme = Programme::create(['montant' => 5000, 'statut' => 'créé']);
        $seance = Seance::create([
            'numero_seance' => 1,
            'date_seance' => today(),
            'heure_debut' => '08:00:00',
            'programme_id' => $programme->id,
        ]);
        $fidele = Fidele::create(['code_fidele' => 'CODE-JOUR-2', 'nom' => 'Doe', 'postnom' => 'X', 'prenom' => 'John', 'genre' => 'M', 'path_qr_code' => 'qr-2.png']);

        $this->postJson(route('presences.store'), ['code_fidele' => $fidele->code_fidele])->assertStatus(201);
        $this->assertEquals(1, Presence::count());

        $this->postJson(route('presences.store'), ['code_fidele' => $fidele->code_fidele])
            ->assertStatus(200)
            ->assertJson(['message' => 'Ce fidèle est déjà enregistré à cette séance.']);

        $this->assertEquals(1, Presence::count());
    }

    public function test_code_inconnu_retourne_422(): void
    {
        $this->postJson(route('presences.store'), ['code_fidele' => 'CODE-INCONNU'])
            ->assertStatus(422)
            ->assertJson(['message' => 'Aucun fidèle ne correspond à ce code.']);
    }

    public function test_erreur_lorsqu_aucune_seance_na_lieu_aujourdhui(): void
    {
        $fidele = Fidele::create(['code_fidele' => 'CODE-JOUR-3', 'nom' => 'Doe', 'postnom' => 'X', 'prenom' => 'Sam', 'genre' => 'M', 'path_qr_code' => 'qr-3.png']);

        $this->postJson(route('presences.store'), ['code_fidele' => $fidele->code_fidele])
            ->assertStatus(422)
            ->assertJson(['message' => 'Aucune séance prévue à la date du jour. Créez ou planifiez une séance.']);
    }
}
