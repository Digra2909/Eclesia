<?php

namespace Tests\Feature;

use App\Models\Fidele;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NouvelUniteStoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * L'enregistrement d'une nouvel unité : le téléphone reçoit le préfixe
     * +243 (le formulaire ne saisit que 9 chiffres) et l'adresse est stockée.
     */
    public function test_store_prepends_plus_243_and_saves_adresse(): void
    {
        $reponse = $this->post(route('nouvel-unite.store'), [
            'nom' => 'Kabongo',
            'postnom' => 'Mbuyi',
            'prenom' => 'Jean-Baptiste',
            'date_naissance' => '1990-05-12',
            'telephone' => '991234567',
            'adresse' => 'Lubumbashi, Kampemba',
            'genre' => 'M',
            'statut_nu' => 'en règle',
        ]);

        $reponse->assertRedirect();
        $reponse->assertSessionHas('success');

        $fidele = Fidele::where('code_fidele', 'like', '%-%')->latest()->first();

        $this->assertNotNull($fidele, 'Un fidèle doit être créé.');
        $this->assertSame('+243991234567', $fidele->telephone);
        $this->assertSame('Lubumbashi, Kampemba', $fidele->adresse);
        $this->assertSame('en règle', $fidele->nu?->statut);
    }

    /**
     * Appel AJAX (JSON) : la réponse est 201 avec un message, pour rester
     * dans la modale après l'enregistrement.
     */
    public function test_store_responds_json_for_ajax_requests(): void
    {
        $reponse = $this->postJson(route('nouvel-unite.store'), [
            'nom' => 'Ilunga',
            'postnom' => 'Mukendi',
            'prenom' => 'Marie',
            'telephone' => '990001122',
            'genre' => 'F',
            'statut_nu' => 'non en règle',
        ]);

        $reponse->assertStatus(201);

        $this->assertStringContainsString('Nouvelle unité créée :', $reponse->json('message'));
    }

    /**
     * Le téléphone doit contenir exactement 9 chiffres (sans le +243).
     */
    public function test_telephone_must_have_exactly_nine_digits(): void
    {
        $this->post(route('nouvel-unite.store'), [
            'nom' => 'Kalonji',
            'postnom' => 'Ngoy',
            'prenom' => 'David',
            'telephone' => '12345',
            'genre' => 'M',
            'statut_nu' => 'en règle',
        ])->assertSessionHasErrors('telephone');
    }
}
