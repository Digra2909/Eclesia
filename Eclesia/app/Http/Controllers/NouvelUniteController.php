<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNouvelUniteRequest;
use App\Models\Fidele;
use App\Models\Nu;
use App\Models\StatutFidele;
use Endroid\QrCode\QrCode;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class NouvelUniteController extends Controller
{
    /**
     * Crée une nouvelle unité : fidèle + NU + QR code + envoi WhatsApp.
     */
    public function store(StoreNouvelUniteRequest $request)
    {
        // 1. Le fidèle est créé avec un code temporaire, remplacé par le code
        //    définitif une fois l'id connu.
        $fidele = Fidele::create([
            'code_fidele' => 'X-'.Str::random(12),
            'nom' => $request->validated('nom'),
            'postnom' => $request->validated('postnom'),
            'prenom' => $request->validated('prenom'),
            'date_naissance' => $request->validated('date_naissance'),
            'telephone' => $request->validated('telephone'),
            'grace' => $request->validated('grace'),
            'genre' => $request->validated('genre'),
            'path_qr_code' => '',
            'statut_id' => $this->statutFidelePour($request->validated('statut_nu')),
        ]);

        // 2. Génération des codes définitifs.
        $codeFidele = $this->generationCodeFidele($fidele->id);
        $codeNu = $this->generationCodeNu($fidele->id);

        $fidele->code_fidele = $codeFidele;
        $fidele->path_qr_code = $this->generationCodeQR($codeFidele);
        $fidele->save();

        // 3. Création de la NU associée.
        $nu = Nu::create([
            'code_nu' => $codeNu,
            'statut' => $request->validated('statut_nu'),
            'fidele_id' => $fidele->id,
        ]);

        // 4. Envoi du QR code et du code en clair par WhatsApp.
        $this->envoiCodeQr($fidele->telephone, $codeFidele, $codeNu);

        return redirect()
            ->route('dashboard')
            ->with('success', "Nouvelle unité créée : {$codeFidele} / {$codeNu}.");
    }

    /**
     * Récupère (ou crée) le statut "en règle"/"non en règle" du fidèle.
     */
    protected function statutFidelePour(string $statutNu): int
    {
        return StatutFidele::firstOrCreate(['designation' => $statutNu])->id;
    }

    /**
     * Génère le code fidèle au format COMP-CNTRL-<id>.
     */
    public function generationCodeFidele(int $id): string
    {
        return 'COMP-CNTRL-'.$id;
    }

    /**
     * Génère le code NU au format NU-<id>.
     */
    public function generationCodeNu(int $id): string
    {
        return 'NU-'.$id;
    }

    /**
     * Génère le QR code encapsulant uniquement le codeFidele.
     * Le fichier PNG est stocké dans storage/app/public/formation/qr-codes.
     */
    public function generationCodeQR(string $codeFidele): string
    {
        $qrCode = QrCode::create($codeFidele)
            ->setSize(300)
            ->setMargin(10);

        $storagePath = 'formation/qr-codes/'.$codeFidele.'.png';
        $absolutePath = storage_path('app/public/'.$storagePath);

        if (! is_dir(dirname($absolutePath))) {
            mkdir(dirname($absolutePath), 0755, true);
        }

        $qrCode->writeFile($absolutePath);

        return $storagePath;
    }

    /**
     * Envoie le QR code (+ code en clair) au numéro WhatsApp renseigné.
     */
    public function envoiCodeQr(?string $numeroWhatsApp, string $codeFidele, string $codeNu): void
    {
        $message = "Pour enregistrer vos présences dans les moments de réunion, présentez ce code pour le scannage.\n\n"
            .'Code fidèle : '.$codeFidele."\n"
            .'Code NU : '.$codeNu;

        $apiUrl = config('services.whatsapp.api_url');
        $apiToken = config('services.whatsapp.api_token');

        if ($apiUrl && $apiToken && $numeroWhatsApp) {
            try {
                Http::withToken($apiToken)->post($apiUrl, [
                    'chat_id' => $numeroWhatsApp,
                    'text' => $message,
                ]);
            } catch (\Exception $e) {
                Log::error("Échec de l'envoi WhatsApp vers {$numeroWhatsApp} : ".$e->getMessage());
            }

            return;
        }

        Log::info('WhatsApp (simulé) envoyé à '.($numeroWhatsApp ?? 'aucun numéro').' — '.$message);
    }
}
