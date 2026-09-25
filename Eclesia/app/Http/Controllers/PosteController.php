<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePosteRequest;
use App\Http\Requests\UpdatePosteRequest;
use App\Models\Poste;

class PosteController extends Controller
{
    /**
     * Liste des enregistrements (JSON si appel AJAX).
     */
    public function index()
    {
        return response()->json(Poste::latest()->get());
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
    public function store(StorePosteRequest $request)
    {
        $poste = Poste::create($request->validated());

        if ($request->wantsJson()) {
            return response()->json($poste, 201);
        }

        return back()->with('success', 'Poste créé avec succès.');
    }

    /**
     * Détail d'une ressource.
     */
    public function show(Poste $poste)
    {
        if (request()->wantsJson()) {
            return response()->json($poste);
        }

        return redirect()->route('dashboard');
    }

    /**
     * Formulaire d'édition : inutile, tout se passe dans les modales.
     */
    public function edit(Poste $poste)
    {
        return redirect()->route('dashboard');
    }

    /**
     * Met à jour une ressource.
     */
    public function update(UpdatePosteRequest $request, Poste $poste)
    {
        $poste->update($request->validated());

        if ($request->wantsJson()) {
            return response()->json($poste);
        }

        return back()->with('success', 'Poste modifié avec succès.');
    }

    /**
     * Supprime une ressource.
     */
    public function destroy(Poste $poste)
    {
        $poste->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Poste supprimé.']);
        }

        return back()->with('success', 'Poste supprimé.');
    }
}
