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
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::get('/candidatures', [AdminCandidatureController::class, 'index'])->name('candidatures.index');
    Route::get('/candidatures/{candidature}', [AdminCandidatureController::class, 'show'])->name('candidatures.show');
    Route::post('/candidatures/{candidature}/entretien', [AdminCandidatureController::class, 'planifierEntretien'])->name('candidatures.entretien');
    Route::post('/candidatures/{candidature}/decision-entretien', [AdminCandidatureController::class, 'deciderApresEntretien'])->name('candidatures.decisionEntretien');
});
