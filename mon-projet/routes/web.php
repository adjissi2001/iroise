<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BeneficiaireController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BienvenueController;
use Illuminate\Support\Facades\Route;


// 🏠 Page d'accueil publique
Route::get('/', [BienvenueController::class, 'index'])->name('home');
Route::get('/bienvenue', [BienvenueController::class, 'index'])->name('bienvenue');

// 📊 Dashboard (authentification requise)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 👤 Routes du profil utilisateur (authentification requise)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 🏥 Routes pour les bénéficiaires (authentification requise)
Route::middleware('auth')->group(function () {
    // Liste des bénéficiaires
    Route::get('/beneficiaires', [BeneficiaireController::class, 'index'])->name('beneficiaire.index');
    
    // Détail d'un bénéficiaire
    Route::get('/beneficiaires/{id}', [BeneficiaireController::class, 'show'])->name('beneficiaire.show');
    
    // Mise à jour d'un bénéficiaire
    Route::put('/beneficiaires/{id}', [BeneficiaireController::class, 'updateSql'])->name('beneficiaire.update');
    
    // Suppression d'un bénéficiaire
    Route::delete('/beneficiaires/{id}', [BeneficiaireController::class, 'destroy'])->name('beneficiaire.destroy');
    
    // Interface d'administration
    Route::get('/administration', [AdminController::class, 'selectBeneficiaires'])->name('admin.beneficiaires');
});

// 👥 Routes pour les utilisateurs (admin seulement)
Route::middleware(['auth'])->group(function () {
    // Liste des utilisateurs
    Route::get('/utilisateurs', [UserController::class, 'index'])->name('user.index');
    
    // Détail d'un utilisateur
    Route::get('/utilisateurs/{id}', [UserController::class, 'show'])->name('user.show');
});

// 🔐 Routes d'authentification Breeze
require __DIR__.'/auth.php';



Route::middleware(['auth'])->group(function () {
    Route::get('/beneficiaires/create', [BeneficiaireController::class, 'create'])->name('beneficiaire.create');
    Route::post('/beneficiaires', [BeneficiaireController::class, 'store'])->name('beneficiaire.store');
});
