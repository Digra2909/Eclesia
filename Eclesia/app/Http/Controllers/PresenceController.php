<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePresenceRequest;
use App\Http\Requests\UpdatePresenceRequest;
use App\Models\Fidele;
use App\Models\Presence;
use App\Models\Seance;

class PresenceController extends Controller
{
    /**
     * Liste des enregistrements (JSON si appel AJAX).
     */
    public function index()
    {
        return response()->json(Presence::latest()->get());
    }

    /**
     * Formulaire : inutile, tout se passe dans les modales du tableau de bord.
     */
    public function create()
    {
        return redirect()->route('dashboard');
    }

    /**
     * Enregistre une présence à partir du code du fidèle (voie prioritaire).
     * Le scanner QR est la voie secondaire : il renvoie le même code_fidele
     * et passe donc par cet unique point d'entrée.
     */
    public function store(StorePresenceRequest $request)
    {
        $fidele = Fidele::where('code_fidele', $request->validated('code_fidele'))->first();

        if (! $fidele) {
            $erreur = 'Aucun fidèle ne correspond à ce code.';

            if ($request->wantsJson()) {
                return response()->json(['message' => $erreur], 422);
            }

            return back()->withErrors(['code_fidele' => $erreur])->withInput();
        }

        // Par défaut, la présence est rattachée à la séance du jour.
        $seance = $request->validated('seance_id')
            ? Seance::find($request->validated('seance_id'))
            : Seance::whereDate('date_seance', today())->first();

        if (! $seance) {
            $erreur = 'Aucune séance prévue à la date du jour.';
            $erreur .= $request->validated('seance_id') ? '' : ' Créez ou planifiez une séance.';

            if ($request->wantsJson()) {
                return response()->json(['message' => $erreur], 422);
            }

            return back()->withErrors(['seance_id' => $erreur])->withInput();
        }

        $presence = Presence::firstOrCreate(
            [
                'fidele_id' => $fidele->id,
                'seance_id' => $seance->id,
            ],
            [
                'est_present' => $request->boolean('est_present', true),
            ]
        );

        $nouvelle = $presence->wasRecentlyCreated;
        $message = $nouvelle
            ? "Présence enregistrée pour {$fidele->prenom} {$fidele->nom}."
            : 'Ce fidèle est déjà enregistré à cette séance.';

        if ($request->wantsJson()) {
            return response()->json([
                'presence' => $presence,
                'nouvelle' => $nouvelle,
                'message' => $message,
            ], $nouvelle ? 201 : 200);
        }

        return back()->with('success', $message);
    }

    /**
     * Détail d'une ressource.
     */
    public function show(Presence $presence)
    {
        if (request()->wantsJson()) {
            return response()->json($presence);
        }

        return redirect()->route('dashboard');
    }

    /**
     * Formulaire d'édition : inutile, tout se passe dans les modales.
     */
    public function edit(Presence $presence)
    {
        return redirect()->route('dashboard');
    }

    /**
     * Met à jour une ressource.
     */
    public function update(UpdatePresenceRequest $request, Presence $presence)
    {
        $presence->update($request->validated());

        if ($request->wantsJson()) {
            return response()->json($presence);
        }

        return back()->with('success', 'Presence modifié avec succès.');
    }

    /**
     * Supprime une ressource.
     */
    public function destroy(Presence $presence)
    {
        $presence->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Presence supprimé.']);
        }

        return back()->with('success', 'Presence supprimé.');
    }
}
