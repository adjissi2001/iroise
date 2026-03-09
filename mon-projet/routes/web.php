<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BeneficiaireController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminMissionController;
use App\Http\Controllers\ReferentController;
use App\Http\Controllers\BenevolController;
use App\Http\Controllers\BienvenueController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureUserIsAdminOrReferent;


// 🏠 Page d'accueil publique
Route::get('/', [BienvenueController::class, 'index'])->name('home');
Route::get('/bienvenue', [BienvenueController::class, 'index'])->name('bienvenue');

// 📊 Dashboard (authentification requise)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'profile.validated', 'verified'])->name('dashboard');

// 👤 Routes du profil utilisateur (authentification requise)
Route::middleware(['auth', 'profile.validated'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 🏥 Routes pour les bénéficiaires (authentification requise)
Route::middleware(['auth', 'profile.validated'])->group(function () {
    // Liste des bénéficiaires
    Route::get('/beneficiaires', [BeneficiaireController::class, 'index'])->name('beneficiaire.index');
    
    // Création d'un bénéficiaire (AVANT la route {id})
    Route::get('/beneficiaires/create', [BeneficiaireController::class, 'create'])->name('beneficiaire.create');
    Route::post('/beneficiaires', [BeneficiaireController::class, 'store'])->name('beneficiaire.store');
    
    // Détail d'un bénéficiaire
    Route::get('/beneficiaires/{id}', [BeneficiaireController::class, 'show'])->name('beneficiaire.show');
    
    // Mise à jour d'un bénéficiaire
    Route::put('/beneficiaires/{id}', [BeneficiaireController::class, 'updateSql'])->name('beneficiaire.update');
    
    // Suppression d'un bénéficiaire
    Route::delete('/beneficiaires/{id}', [BeneficiaireController::class, 'destroy'])->name('beneficiaire.destroy');
    
    // Interface d'administration
    Route::get('/administration', [AdminController::class, 'selectBeneficiaires'])
        ->middleware([EnsureUserIsAdminOrReferent::class])
        ->name('admin.beneficiaires');

    // Admin Missions (module séparé)
    Route::middleware(['profile.validated', 'admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/missions', [AdminMissionController::class, 'index'])->name('missions.index');
        Route::post('/missions', [AdminMissionController::class, 'store'])->name('missions.store');
        Route::get('/missions/{mission}/edit', [AdminMissionController::class, 'edit'])->name('missions.edit');
        Route::put('/missions/{mission}', [AdminMissionController::class, 'update'])->name('missions.update');
        Route::post('/missions/{mission}/annuler', [AdminMissionController::class, 'annuler'])->name('missions.annuler');
    });
});

// 👥 Routes pour les utilisateurs (admin seulement)
Route::middleware(['auth', 'profile.validated'])->group(function () {
    // Liste des utilisateurs
    Route::get('/utilisateurs', [UserController::class, 'index'])->name('user.index');
    
    // Détail d'un utilisateur
    Route::get('/utilisateurs/{id}', [UserController::class, 'show'])->name('user.show');
});

// Routes d'édition/suppression réservées aux administrateurs
Route::middleware(['auth', 'profile.validated', 'admin'])->group(function () {
    Route::get('/utilisateurs/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/utilisateurs/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/utilisateurs/{id}', [UserController::class, 'destroy'])->name('user.destroy');

    // Suppression manuelle des inscriptions en attente dépassant le délai
    Route::delete('/utilisateurs/pending/expired', [UserController::class, 'destroyExpiredPending'])->name('user.destroyExpiredPending');
});

// Routes pour l'espace référent (authentification requise)
Route::middleware(['auth', 'profile.validated'])->group(function () {
    // Espace référent
    Route::get('/referent', [ReferentController::class, 'index'])->name('referent.index');
});
// Routes pour l'espace bénévole (authentification requise)
Route::middleware(['auth', 'profile.validated'])->group(function () {
    // Espace bénévole
    Route::get('/benevole', [BenevolController::class, 'index'])->name('benevole.index');
});
// Routes d'authentification Breeze
require __DIR__.'/auth.php';

