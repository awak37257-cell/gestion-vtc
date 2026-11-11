<?php

use App\Http\Controllers\Admin\CandidatureController as AdminCandidatureController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CandidatureController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/candidater', [CandidatureController::class, 'create'])->name('candidature.create');
Route::post('/candidater', [CandidatureController::class, 'store'])->name('candidature.store');
Route::get('/connexion', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/connexion', [AuthController::class, 'login'])->name('login.post');
Route::post('/deconnexion', [AuthController::class, 'logout'])->name('logout');

// Zone Administration
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::get('/candidatures', [AdminCandidatureController::class, 'index'])->name('candidatures.index');
    Route::get('/candidatures/{candidature}', [AdminCandidatureController::class, 'show'])->name('candidatures.show');
    Route::post('/candidatures/{candidature}/entretien', [AdminCandidatureController::class, 'planifierEntretien'])->name('candidatures.entretien');
    Route::post('/candidatures/{candidature}/decision-entretien', [AdminCandidatureController::class, 'deciderApresEntretien'])->name('candidatures.decisionEntretien');

    // Bilan de la journée & Notifications groupées (Nom complet : admin.entretiens.recapitulatif)
    Route::get('/entretiens/recapitulatif', [AdminCandidatureController::class, 'recapitulatifJournee'])->name('entretiens.recapitulatif');
    Route::post('/entretiens/envoyer-groupes', [AdminCandidatureController::class, 'envoyerGroupesDuJour'])->name('candidatures.envoyerGroupesDuJour');
    Route::post('/candidatures/{id}/entretien/modifier', [App\Http\Controllers\Admin\CandidatureController::class, 'modifierEntretien'])
    ->name('candidatures.modifierEntretien');
    Route::delete('/candidatures/{id}/entretien/annuler', [App\Http\Controllers\Admin\CandidatureController::class, 'annulerEntretien'])
    ->name('candidatures.annulerEntretien');
});