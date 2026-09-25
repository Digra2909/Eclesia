<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInterventionRequest;
use App\Http\Requests\UpdateInterventionRequest;
use App\Models\Intervention;

class InterventionController extends Controller
{
    /**
     * Liste des enregistrements (JSON si appel AJAX).
     */
    public function index()
    {
        return response()->json(Intervention::latest()->get());
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
    public function store(StoreInterventionRequest $request)
    {
        $intervention = Intervention::create($request->validated());

        if ($request->wantsJson()) {
            return response()->json($intervention, 201);
        }

        return back()->with('success', 'Intervention créé avec succès.');
    }

    /**
     * Détail d'une ressource.
     */
    public function show(Intervention $intervention)
    {
        if (request()->wantsJson()) {
            return response()->json($intervention);
        }

        return redirect()->route('dashboard');
    }

    /**
     * Formulaire d'édition : inutile, tout se passe dans les modales.
     */
    public function edit(Intervention $intervention)
    {
        return redirect()->route('dashboard');
    }

    /**
     * Met à jour une ressource.
     */
    public function update(UpdateInterventionRequest $request, Intervention $intervention)
    {
        $intervention->update($request->validated());

        if ($request->wantsJson()) {
            return response()->json($intervention);
        }

        return back()->with('success', 'Intervention modifié avec succès.');
    }

    /**
     * Supprime une ressource.
     */
    public function destroy(Intervention $intervention)
    {
        $intervention->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Intervention supprimé.']);
        }

        return back()->with('success', 'Intervention supprimé.');
    }
}
