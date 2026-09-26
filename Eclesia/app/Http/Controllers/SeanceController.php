<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSeanceRequest;
use App\Http\Requests\UpdateSeanceRequest;
use App\Models\Seance;

class SeanceController extends Controller
{
    /**
     * Page de planification et suivi des séances.
     */
    public function index()
    {
        return view('formation.pages.seances.index');
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
    public function store(StoreSeanceRequest $request)
    {
        $seance = Seance::create($request->validated());

        if ($request->wantsJson()) {
            return response()->json($seance, 201);
        }

        return back()->with('success', 'Séance créée avec succès.');
    }

    /**
     * Détail d'une ressource.
     */
    public function show(Seance $seance)
    {
        if (request()->wantsJson()) {
            return response()->json($seance);
        }

        return redirect()->route('dashboard');
    }

    /**
     * Formulaire d'édition : inutile, tout se passe dans les modales.
     */
    public function edit(Seance $seance)
    {
        return redirect()->route('dashboard');
    }

    /**
     * Met à jour une ressource.
     */
    public function update(UpdateSeanceRequest $request, Seance $seance)
    {
        $seance->update($request->validated());

        if ($request->wantsJson()) {
            return response()->json($seance);
        }

        return back()->with('success', 'Seance modifié avec succès.');
    }

    /**
     * Supprime une ressource.
     */
    public function destroy(Seance $seance)
    {
        $seance->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Seance supprimé.']);
        }

        return back()->with('success', 'Seance supprimé.');
    }
}
