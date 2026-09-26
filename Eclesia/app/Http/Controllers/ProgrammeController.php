<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProgrammeCoursRequest;
use App\Http\Requests\StoreProgrammeRequest;
use App\Http\Requests\UpdateProgrammeRequest;
use App\Models\Cours;
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
     * Associe des cours à un programme (et en crée éventuellement un nouveau),
     * puis bascule vers l'ajout des séances.
     */
    public function storeCours(StoreProgrammeCoursRequest $request, Programme $programme)
    {
        $coursIds = $request->validated('cours_ids', []);
        $nouveauCours = null;

        if ($request->filled('nouveau_passages')) {
            $nouveauCours = Cours::create(['passages' => $request->validated('nouveau_passages')]);
            $programme->cours()->attach($nouveauCours->id);
        }

        if (! empty($coursIds)) {
            $programme->cours()->sync(array_unique($coursIds));
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Cours associé au programme.',
                'cours' => $nouveauCours ? ['id' => $nouveauCours->id, 'passages' => $nouveauCours->passages] : null,
            ]);
        }

        return redirect()->route('dashboard')
            ->with('success', 'Cours associés au programme. Ajoutez maintenant les séances.')
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
