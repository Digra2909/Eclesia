<?php

namespace App\Http\Controllers;

class ParametreController extends Controller
{
    /**
     * Page des paramètres (statuts, types d'intervention, postes, cours).
     */
    public function index()
    {
        return view('formation.pages.parametres.index');
    }
}
