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
     * Page de gestion des programmes (création + liste).
     */
    public function index()
    {
        return view('formation.pages.programmes.index');
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
        $programme = Programme::create([
            'statut' => 'créé',
            'commentaire' => $request->validated('commentaire'),
        ]);

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
            ->with('success', 'Programme créé avec succès. Associez vos cours, puis ajoutez les séances.')
            ->with('cours_pour_programme', $programme->id);
    }

    /**
     * Page de détail d'un programme (cours, statut et séances).
     */
    public function show(Programme $programme)
    {
        if (request()->wantsJson()) {
            return response()->json($programme);
        }

        return view('formation.pages.programmes.show', compact('programme'));
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
