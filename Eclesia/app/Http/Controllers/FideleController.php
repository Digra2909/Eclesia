<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFideleRequest;
use App\Http\Requests\UpdateFideleRequest;
use App\Models\Fidele;

class FideleController extends Controller
{
    /**
     * Liste des enregistrements (JSON si appel AJAX).
     */
    public function index()
    {
        return response()->json(Fidele::latest()->get());
    }

    /**
     * Formulaire : inutile, tout se passe dans les modales du tableau de bord.
     */
    public function create()
    {
        return redirect()->route('dashboard');
    }

    /**
     * Enregistre une nouvelle ressource.
     */
    public function store(StoreFideleRequest $request)
    {
        $fidele = Fidele::create($request->validated());

        if ($request->wantsJson()) {
            return response()->json($fidele, 201);
        }

        return back()->with('success', 'Fidele créé avec succès.');
    }

    /**
     * Détail d'une ressource.
     */
    public function show(Fidele $fidele)
    {
        if (request()->wantsJson()) {
            return response()->json($fidele);
        }

        return redirect()->route('dashboard');
    }

    /**
     * Formulaire d'édition : inutile, tout se passe dans les modales.
     */
    public function edit(Fidele $fidele)
    {
        return redirect()->route('dashboard');
    }

    /**
     * Met à jour une ressource.
     */
    public function update(UpdateFideleRequest $request, Fidele $fidele)
    {
        $data = $request->validated();

        // Normalisation du téléphone : 9 chiffres saisis → préfixe +243.
        if ($data['telephone'] && ! str_starts_with($data['telephone'], '+')) {
            $data['telephone'] = '+243'.ltrim($data['telephone'], '0');
        }

        // Le statut NU est rattaché à la table `nus`, pas `fideles`.
        $statutNu = $data['statut_nu'] ?? null;
        unset($data['statut_nu']);

        $fidele->update($data);

        if ($statutNu && $fidele->nu) {
            $fidele->nu->update(['statut' => $statutNu]);
        }

        if ($request->wantsJson()) {
            return response()->json($fidele);
        }

        return back()->with('success', 'Fidele modifié avec succès.');
    }

    /**
     * Supprime une ressource.
     */
    public function destroy(Fidele $fidele)
    {
        $fidele->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Fidele supprimé.']);
        }

        return back()->with('success', 'Fidele supprimé.');
    }
}
