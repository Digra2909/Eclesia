<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFormationNuRequest;
use App\Http\Requests\UpdateFormationNuRequest;
use App\Models\FormationNu;

class FormationNuController extends Controller
{
    /**
     * Liste des enregistrements (JSON si appel AJAX).
     */
    public function index()
    {
        return response()->json(FormationNu::latest()->get());
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
    public function store(StoreFormationNuRequest $request)
    {
        $formationNu = FormationNu::create($request->validated());

        if ($request->wantsJson()) {
            return response()->json($formationNu, 201);
        }

        return back()->with('success', 'FormationNu créé avec succès.');
    }

    /**
     * Détail d'une ressource.
     */
    public function show(FormationNu $formationNu)
    {
        if (request()->wantsJson()) {
            return response()->json($formationNu);
        }

        return redirect()->route('dashboard');
    }

    /**
     * Formulaire d'édition : inutile, tout se passe dans les modales.
     */
    public function edit(FormationNu $formationNu)
    {
        return redirect()->route('dashboard');
    }

    /**
     * Met à jour une ressource.
     */
    public function update(UpdateFormationNuRequest $request, FormationNu $formationNu)
    {
        $formationNu->update($request->validated());

        if ($request->wantsJson()) {
            return response()->json($formationNu);
        }

        return back()->with('success', 'FormationNu modifié avec succès.');
    }

    /**
     * Supprime une ressource.
     */
    public function destroy(FormationNu $formationNu)
    {
        $formationNu->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'FormationNu supprimé.']);
        }

        return back()->with('success', 'FormationNu supprimé.');
    }
}
