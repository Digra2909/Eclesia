<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStatutFideleRequest;
use App\Http\Requests\UpdateStatutFideleRequest;
use App\Models\StatutFidele;

class StatutFideleController extends Controller
{
    /**
     * Liste des enregistrements (JSON si appel AJAX).
     */
    public function index()
    {
        return response()->json(StatutFidele::latest()->get());
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
    public function store(StoreStatutFideleRequest $request)
    {
        $statutFidele = StatutFidele::create($request->validated());

        if ($request->wantsJson()) {
            return response()->json($statutFidele, 201);
        }

        return back()->with('success', 'StatutFidele créé avec succès.');
    }

    /**
     * Détail d'une ressource.
     */
    public function show(StatutFidele $statutFidele)
    {
        if (request()->wantsJson()) {
            return response()->json($statutFidele);
        }

        return redirect()->route('dashboard');
    }

    /**
     * Formulaire d'édition : inutile, tout se passe dans les modales.
     */
    public function edit(StatutFidele $statutFidele)
    {
        return redirect()->route('dashboard');
    }

    /**
     * Met à jour une ressource.
     */
    public function update(UpdateStatutFideleRequest $request, StatutFidele $statutFidele)
    {
        $statutFidele->update($request->validated());

        if ($request->wantsJson()) {
            return response()->json($statutFidele);
        }

        return back()->with('success', 'StatutFidele modifié avec succès.');
    }

    /**
     * Supprime une ressource.
     */
    public function destroy(StatutFidele $statutFidele)
    {
        $statutFidele->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'StatutFidele supprimé.']);
        }

        return back()->with('success', 'StatutFidele supprimé.');
    }
}
