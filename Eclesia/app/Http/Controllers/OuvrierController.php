<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOuvrierRequest;
use App\Http\Requests\UpdateOuvrierRequest;
use App\Models\Ouvrier;

class OuvrierController extends Controller
{
    /**
     * Liste des enregistrements (JSON si appel AJAX).
     */
    public function index()
    {
        return response()->json(Ouvrier::latest()->get());
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
    public function store(StoreOuvrierRequest $request)
    {
        $ouvrier = Ouvrier::create($request->validated());

        if ($request->wantsJson()) {
            return response()->json($ouvrier, 201);
        }

        return back()->with('success', 'Ouvrier créé avec succès.');
    }

    /**
     * Détail d'une ressource.
     */
    public function show(Ouvrier $ouvrier)
    {
        if (request()->wantsJson()) {
            return response()->json($ouvrier);
        }

        return redirect()->route('dashboard');
    }

    /**
     * Formulaire d'édition : inutile, tout se passe dans les modales.
     */
    public function edit(Ouvrier $ouvrier)
    {
        return redirect()->route('dashboard');
    }

    /**
     * Met à jour une ressource.
     */
    public function update(UpdateOuvrierRequest $request, Ouvrier $ouvrier)
    {
        $ouvrier->update($request->validated());

        if ($request->wantsJson()) {
            return response()->json($ouvrier);
        }

        return back()->with('success', 'Ouvrier modifié avec succès.');
    }

    /**
     * Supprime une ressource.
     */
    public function destroy(Ouvrier $ouvrier)
    {
        $ouvrier->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Ouvrier supprimé.']);
        }

        return back()->with('success', 'Ouvrier supprimé.');
    }
}
