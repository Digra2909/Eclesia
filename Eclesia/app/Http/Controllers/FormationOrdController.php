<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFormationOrdRequest;
use App\Http\Requests\UpdateFormationOrdRequest;
use App\Models\FormationOrd;

class FormationOrdController extends Controller
{
    /**
     * Liste des enregistrements (JSON si appel AJAX).
     */
    public function index()
    {
        return response()->json(FormationOrd::latest()->get());
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
    public function store(StoreFormationOrdRequest $request)
    {
        $formationOrd = FormationOrd::create($request->validated());

        if ($request->wantsJson()) {
            return response()->json($formationOrd, 201);
        }

        return back()->with('success', 'FormationOrd créé avec succès.');
    }

    /**
     * Détail d'une ressource.
     */
    public function show(FormationOrd $formationOrd)
    {
        if (request()->wantsJson()) {
            return response()->json($formationOrd);
        }

        return redirect()->route('dashboard');
    }

    /**
     * Formulaire d'édition : inutile, tout se passe dans les modales.
     */
    public function edit(FormationOrd $formationOrd)
    {
        return redirect()->route('dashboard');
    }

    /**
     * Met à jour une ressource.
     */
    public function update(UpdateFormationOrdRequest $request, FormationOrd $formationOrd)
    {
        $formationOrd->update($request->validated());

        if ($request->wantsJson()) {
            return response()->json($formationOrd);
        }

        return back()->with('success', 'FormationOrd modifié avec succès.');
    }

    /**
     * Supprime une ressource.
     */
    public function destroy(FormationOrd $formationOrd)
    {
        $formationOrd->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'FormationOrd supprimé.']);
        }

        return back()->with('success', 'FormationOrd supprimé.');
    }
}
