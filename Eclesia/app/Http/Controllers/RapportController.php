<?php

namespace App\Http\Controllers;

class RapportController extends Controller
{
    /**
     * Page « Rapport » — module à développer.
     */
    public function index()
    {
        return view('formation.pages.rapport.index');
    }
}
