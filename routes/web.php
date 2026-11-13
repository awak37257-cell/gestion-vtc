<?php

use App\Http\Controllers\Admin\CandidatureController as AdminCandidatureController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CandidatureController;
use App\Http\Controllers\DocumentCorrectionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/candidater', [CandidatureController::class, 'create'])->name('candidature.create');
Route::post('/candidater', [CandidatureController::class, 'store'])->name('candidature.store');
Route::get('/connexion', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/connexion', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.post');
Route::post('/deconnexion', [AuthController::class, 'logout'])->name('logout');

// Le candidat renvoie les documents rejetés via un lien personnel (sans compte)
Route::middleware('throttle:20,1')->group(function () {
    Route::get('/documents/corriger/merci', [DocumentCorrectionController::class, 'merci'])->name('documents.correction.merci');
    Route::get('/documents/corriger/{jeton}', [DocumentCorrectionController::class, 'show'])->name('documents.correction');
    Route::post('/documents/corriger/{jeton}', [DocumentCorrectionController::class, 'store'])->name('documents.correction.store');
});

// Zone Administration : connexion obligatoire + compte administrateur
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::get('/candidatures', [AdminCandidatureController::class, 'index'])->name('candidatures.index');
    Route::get('/candidatures/export', [AdminCandidatureController::class, 'export'])->name('candidatures.export');
    Route::get('/candidatures/{candidature}', [AdminCandidatureController::class, 'show'])->name('candidatures.show');
    Route::post('/candidatures/{candidature}/entretien', [AdminCandidatureController::class, 'planifierEntretien'])->name('candidatures.entretien');
    Route::post('/candidatures/{candidature}/decision-entretien', [AdminCandidatureController::class, 'deciderApresEntretien'])->name('candidatures.decisionEntretien');
    Route::post('/candidatures/{candidature}/absent', [AdminCandidatureController::class, 'marquerAbsent'])->name('candidatures.absent');
    Route::post('/candidatures/{candidature}/refus-absence', [AdminCandidatureController::class, 'refuserApresAbsence'])->name('candidatures.refusAbsence');
    Route::post('/candidatures/{candidature}/demande-correction', [AdminCandidatureController::class, 'demanderCorrection'])->name('candidatures.demandeCorrection');

    // Documents des candidats (stockage privé, servis uniquement aux admins)
    Route::get('/documents/{document}', [AdminCandidatureController::class, 'document'])->name('documents.show');
    Route::post('/documents/{document}/validation', [AdminCandidatureController::class, 'validerDocument'])->name('documents.validation');

    // Bilan de la journée & Notifications groupées
    Route::get('/entretiens/recapitulatif', [AdminCandidatureController::class, 'recapitulatifJournee'])->name('entretiens.recapitulatif');
    Route::post('/entretiens/envoyer-groupes', [AdminCandidatureController::class, 'envoyerGroupesDuJour'])->name('candidatures.envoyerGroupesDuJour');
    Route::post('/candidatures/{id}/entretien/modifier', [AdminCandidatureController::class, 'modifierEntretien'])->name('candidatures.modifierEntretien');
    Route::delete('/candidatures/{id}/entretien/annuler', [AdminCandidatureController::class, 'annulerEntretien'])->name('candidatures.annulerEntretien');
});