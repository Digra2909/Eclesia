<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCoursRequest;
use App\Http\Requests\UpdateCoursRequest;
use App\Models\Cours;

class CoursController extends Controller
{
    /**
     * Liste des enregistrements (JSON si appel AJAX).
     */
    public function index()
    {
        return response()->json(Cours::latest()->get());
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
    public function store(StoreCoursRequest $request)
    {
        $cours = Cours::create($request->validated());

        if ($request->wantsJson()) {
            return response()->json($cours, 201);
        }

        return back()->with('success', 'Cours créé avec succès.');
    }

    /**
     * Détail d'une ressource.
     */
    public function show(Cours $cours)
    {
        if (request()->wantsJson()) {
            return response()->json($cours);
        }

        return redirect()->route('dashboard');
    }

    /**
     * Formulaire d'édition : inutile, tout se passe dans les modales.
     */
    public function edit(Cours $cours)
    {
        return redirect()->route('dashboard');
    }

    /**
     * Met à jour une ressource.
     */
    public function update(UpdateCoursRequest $request, Cours $cours)
    {
        $cours->update($request->validated());

        if ($request->wantsJson()) {
            return response()->json($cours);
        }

        return back()->with('success', 'Cours modifié avec succès.');
    }

    /**
     * Supprime une ressource.
     */
    public function destroy(Cours $cours)
    {
        $cours->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Cours supprimé.']);
        }

        return back()->with('success', 'Cours supprimé.');
    }
}
