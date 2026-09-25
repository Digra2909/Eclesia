<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProgrammeRequest;
use App\Http\Requests\UpdateProgrammeRequest;
use App\Models\FormationNu;
use App\Models\FormationOrd;
use App\Models\Programme;

class ProgrammeController extends Controller
{
    /**
     * Liste des enregistrements (JSON si appel AJAX).
     */
    public function index()
    {
        return response()->json(Programme::latest()->get());
    }

    /**
     * Formulaire : inutile, tout se passe dans les modales du tableau de bord.
     */
    public function create()
    {
        return redirect()->route('dashboard');
    }

    /**
     * Enregistre un programme et, selon le type choisi, la formation NU
     * (session) ou la formation Ord (thème) qui lui est rattachée.
     */
    public function store(StoreProgrammeRequest $request)
    {
        $programme = Programme::create($request->safe()->only(['montant', 'statut', 'commentaire']));

        if ($request->input('type') === 'Ord') {
            FormationOrd::create([
                'theme' => $request->input('theme'),
                'programme_id' => $programme->id,
            ]);
        } else {
            FormationNu::create([
                'session' => $request->input('session'),
                'programme_id' => $programme->id,
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json($programme, 201);
        }

        return redirect()->route('dashboard')
            ->with('success', 'Programme créé avec succès. Ajoutez ses séances.')
            ->with('seances_pour_programme', $programme->id);
    }

    /**
     * Détail d'une ressource.
     */
    public function show(Programme $programme)
    {
        if (request()->wantsJson()) {
            return response()->json($programme);
        }

        return redirect()->route('dashboard');
    }

    /**
     * Formulaire d'édition : inutile, tout se passe dans les modales.
     */
    public function edit(Programme $programme)
    {
        return redirect()->route('dashboard');
    }

    /**
     * Met à jour une ressource.
     */
    public function update(UpdateProgrammeRequest $request, Programme $programme)
    {
        $programme->update($request->validated());

        if ($request->wantsJson()) {
            return response()->json($programme);
        }

        return back()->with('success', 'Programme modifié avec succès.');
    }

    /**
     * Supprime une ressource.
     */
    public function destroy(Programme $programme)
    {
        $programme->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Programme supprimé.']);
        }

        return back()->with('success', 'Programme supprimé.');
    }
}
