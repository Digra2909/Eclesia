<?php

use App\Http\Controllers\CoursController;
use App\Http\Controllers\CoursProgrammeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FideleController;
use App\Http\Controllers\FormationNuController;
use App\Http\Controllers\FormationOrdController;
use App\Http\Controllers\InterventionController;
use App\Http\Controllers\NouvelUniteController;
use App\Http\Controllers\NuController;
use App\Http\Controllers\OuvrierController;
use App\Http\Controllers\PosteController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\ProgrammeController;
use App\Http\Controllers\SeanceController;
use App\Http\Controllers\StatutFideleController;
use App\Http\Controllers\TypeInterventionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Enregistrement d'une nouvelle unité (fidèle + NU + QR + WhatsApp).
Route::post('/nouvel-unite', [NouvelUniteController::class, 'store'])->name('nouvel-unite.store');

// Un contrôleur CRUD par modèle (idemtiques : store/update via FormRequest).
Route::resource('fideles', FideleController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
Route::resource('postes', PosteController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
Route::resource('statut-fideles', StatutFideleController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
Route::resource('type-interventions', TypeInterventionController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
Route::resource('nus', NuController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
Route::resource('ouvriers', OuvrierController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
Route::resource('presences', PresenceController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
Route::resource('interventions', InterventionController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
Route::resource('seances', SeanceController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
Route::post('/seances/batch', [SeanceController::class, 'storeBatch'])->name('seances.batch');
Route::resource('programmes', ProgrammeController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
Route::resource('cours', CoursController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
Route::resource('formation-nus', FormationNuController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
Route::resource('formation-ords', FormationOrdController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
Route::resource('cours-programmes', CoursProgrammeController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
