<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTypeInterventionRequest;
use App\Http\Requests\UpdateTypeInterventionRequest;
use App\Models\TypeIntervention;

class TypeInterventionController extends Controller
{
    /**
     * Liste des enregistrements (JSON si appel AJAX).
     */
    public function index()
    {
        return response()->json(TypeIntervention::latest()->get());
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
    public function store(StoreTypeInterventionRequest $request)
    {
        $typeIntervention = TypeIntervention::create($request->validated());

        if ($request->wantsJson()) {
            return response()->json($typeIntervention, 201);
        }

        return back()->with('success', 'TypeIntervention créé avec succès.');
    }

    /**
     * Détail d'une ressource.
     */
    public function show(TypeIntervention $typeIntervention)
    {
        if (request()->wantsJson()) {
            return response()->json($typeIntervention);
        }

        return redirect()->route('dashboard');
    }

    /**
     * Formulaire d'édition : inutile, tout se passe dans les modales.
     */
    public function edit(TypeIntervention $typeIntervention)
    {
        return redirect()->route('dashboard');
    }

    /**
     * Met à jour une ressource.
     */
    public function update(UpdateTypeInterventionRequest $request, TypeIntervention $typeIntervention)
    {
        $typeIntervention->update($request->validated());

        if ($request->wantsJson()) {
            return response()->json($typeIntervention);
        }

        return back()->with('success', 'TypeIntervention modifié avec succès.');
    }

    /**
     * Supprime une ressource.
     */
    public function destroy(TypeIntervention $typeIntervention)
    {
        $typeIntervention->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'TypeIntervention supprimé.']);
        }

        return back()->with('success', 'TypeIntervention supprimé.');
    }
}
