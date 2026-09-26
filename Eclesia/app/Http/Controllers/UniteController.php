<?php

namespace App\Http\Controllers;

use App\Models\Fidele;

class UniteController extends Controller
{
    /**
     * Page de gestion des nouvelles unités (formulaire Livewire + liste).
     */
    public function index()
    {
        return view('formation.pages.unites.index', [
            'totalUnites' => Fidele::count(),
        ]);
    }
}
