<?php

namespace App\Http\Controllers;

use App\Models\Nu;

class EvaluationController extends Controller
{
    /**
     * Page de cotation des nouvelles unités.
     */
    public function index()
    {
        return view('formation.pages.evaluations.index', [
            'nus' => Nu::with('fidele')->latest()->get(),
        ]);
    }
}
