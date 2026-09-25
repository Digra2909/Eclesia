<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCoursProgrammeRequest;
use App\Http\Requests\UpdateCoursProgrammeRequest;
use App\Models\CoursProgramme;

class CoursProgrammeController extends Controller
{
    /**
     * Liste des enregistrements (JSON si appel AJAX).
     */
    public function index()
    {
        return response()->json(CoursProgramme::latest()->get());
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
    public function store(StoreCoursProgrammeRequest $request)
    {
        $coursProgramme = CoursProgramme::create($request->validated());

        if ($request->wantsJson()) {
            return response()->json($coursProgramme, 201);
        }

        return back()->with('success', 'CoursProgramme créé avec succès.');
    }

    /**
     * Détail d'une ressource.
     */
    public function show(CoursProgramme $coursProgramme)
    {
        if (request()->wantsJson()) {
            return response()->json($coursProgramme);
        }

        return redirect()->route('dashboard');
    }

    /**
     * Formulaire d'édition : inutile, tout se passe dans les modales.
     */
    public function edit(CoursProgramme $coursProgramme)
    {
        return redirect()->route('dashboard');
    }

    /**
     * Met à jour une ressource.
     */
    public function update(UpdateCoursProgrammeRequest $request, CoursProgramme $coursProgramme)
    {
        $coursProgramme->update($request->validated());

        if ($request->wantsJson()) {
            return response()->json($coursProgramme);
        }

        return back()->with('success', 'CoursProgramme modifié avec succès.');
    }

    /**
     * Supprime une ressource.
     */
    public function destroy(CoursProgramme $coursProgramme)
    {
        $coursProgramme->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'CoursProgramme supprimé.']);
        }

        return back()->with('success', 'CoursProgramme supprimé.');
    }
}
