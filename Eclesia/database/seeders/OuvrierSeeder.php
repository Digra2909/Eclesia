<?php

namespace Database\Seeders;

use App\Models\Fidele;
use App\Models\Ouvrier;
use App\Models\Poste;
use App\Models\StatutFidele;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OuvrierSeeder extends Seeder
{
    /**
     * Postes types de l'église. La liste est volontairement générique,
     * on peut la compléter au fil de l'eau via les paramètres.
     *
     * @var array<string>
     */
    public const POSTES = [
        'Pasteur',
        'Évangéliste',
        'Président',
        'Secrétaire',
        'Trésorier',
        'Choriste',
        'Enseignant',
    ];

    /**
     * Fidèles d'exemple : [prénom, nom, postnom, genre, adresse].
     *
     * @var array<array{string}>
     */
    public const FIDELES = [
        ['Jean-Baptiste', 'Mbuyi', 'Kabongo', 'M', 'Lubumbashi, commune de Kampemba'],
        ['Marie', 'Ilunga', 'Mukendi', 'F', 'Lubumbashi, commune de Kenya'],
        ['Samuel', 'Tshimanga', 'Mutombo', 'M', 'Lubumbashi, commune de Katuba'],
        ['Esther', 'Kasongo', 'Mwamba', 'F', 'Lubumbashi, commune de Lubumbashi'],
        ['David', 'Ngoy', 'Kalonji', 'M', 'Lubumbashi, commune de Ruashi'],
        ['Ruth', 'Mwepu', 'Kanku', 'F', 'Lubumbashi, commune de Kampemba'],
        ['Joseph', 'Kalenga', 'Mukendi', 'M', 'Lubumbashi, commune de Kenya'],
        ['Dorcas', 'Banza', 'Tshiala', 'F', 'Lubumbashi, commune de Katuba'],
        ['Pierre', 'Nkulu', 'Kanyinda', 'M', 'Lubumbashi, commune de Lubumbashi'],
        ['Debora', 'Mwanza', 'Mbuyamba', 'F', 'Lubumbashi, commune de Ruashi'],
    ];

    public function run(): void
    {
        $postes = collect(self::POSTES)->map(
            fn (string $designation) => Poste::firstOrCreate(['designation' => $designation])
        );

        $statut = StatutFidele::firstOrCreate(['designation' => 'ouvrier']);

        foreach (self::FIDELES as $index => [$prenom, $nom, $postnom, $genre, $adresse]) {
            $telephone = '+2439'.str_pad((string) (700000000 + $index * 111111), 8, '0', STR_PAD_LEFT);

            $fidele = Fidele::create([
                'code_fidele' => 'FID-'.str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT),
                'nom' => $nom,
                'postnom' => $postnom,
                'prenom' => $prenom,
                'date_naissance' => Carbon::parse('1985-01-01')->addDays($index * 37)->format('Y-m-d'),
                'telephone' => $telephone,
                'adresse' => $adresse,
                'genre' => $genre,
                'statut_id' => $statut->id,
            ]);

            Ouvrier::create([
                'code_ouvrier' => 'OUV-'.str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT),
                'poste_id' => $postes[$index % $postes->count()]->id,
                'fidele_id' => $fidele->id,
                'path_photo' => null,
            ]);
        }
    }
}
