<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNuRequest;
use App\Http\Requests\UpdateNuRequest;
use App\Models\Nu;

class NuController extends Controller
{
    /**
     * Liste des enregistrements (JSON si appel AJAX).
     */
    public function index()
    {
        return response()->json(Nu::latest()->get());
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
    public function store(StoreNuRequest $request)
    {
        $nu = Nu::create($request->validated());

        if ($request->wantsJson()) {
            return response()->json($nu, 201);
        }

        return back()->with('success', 'Nu créé avec succès.');
    }

    /**
     * Détail d'une ressource.
     */
    public function show(Nu $nu)
    {
        if (request()->wantsJson()) {
            return response()->json($nu);
        }

        return redirect()->route('dashboard');
    }

    /**
     * Formulaire d'édition : inutile, tout se passe dans les modales.
     */
    public function edit(Nu $nu)
    {
        return redirect()->route('dashboard');
    }

    /**
     * Met à jour une ressource.
     */
    public function update(UpdateNuRequest $request, Nu $nu)
    {
        $nu->update($request->validated());

        if ($request->wantsJson()) {
            return response()->json($nu);
        }

        return back()->with('success', 'Nu modifié avec succès.');
    }

    /**
     * Supprime une ressource.
     */
    public function destroy(Nu $nu)
    {
        $nu->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Nu supprimé.']);
        }

        return back()->with('success', 'Nu supprimé.');
    }
}
