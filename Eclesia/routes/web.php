<?php

use App\Http\Controllers\CoursController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\FideleController;
use App\Http\Controllers\InterventionController;
use App\Http\Controllers\ParametreController;
use App\Http\Controllers\PosteController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\ProgrammeController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\SeanceController;
use App\Http\Controllers\StatutFideleController;
use App\Http\Controllers\TypeInterventionController;
use App\Http\Controllers\UniteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Pages principales de l'application Formation.
Route::get('/unites', [UniteController::class, 'index'])->name('unites.index');
Route::get('/evaluations', [EvaluationController::class, 'index'])->name('evaluations.index');
Route::get('/rapport', [RapportController::class, 'index'])->name('rapport.index');
Route::get('/parametres', [ParametreController::class, 'index'])->name('parametres.index');

// Un contrôleur CRUD par modèle (store/update via FormRequest). Plusieurs
// endpoints ne sont plus appelés par l'UI (création/édition passées en
// Livewire) — seul « destroy » sert encore pour les suppressions.
Route::resource('fideles', FideleController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
Route::resource('postes', PosteController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
Route::resource('statut-fideles', StatutFideleController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
Route::resource('type-interventions', TypeInterventionController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
Route::resource('presences', PresenceController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
Route::resource('interventions', InterventionController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
Route::resource('seances', SeanceController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
Route::resource('programmes', ProgrammeController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
Route::resource('cours', CoursController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
