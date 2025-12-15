<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutoController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\BeneficiaireController;


Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/bienvenue', [App\Http\Controllers\BienvenueController::class, 'index']);

Route::get('/register', [RegisterController::class, 'inscriptionForm'])->name('register');


Route::get('/login', [AutoController::class, 'showLoginForm'])->name('login');

Route::post('/login', [AutoController::class, 'login'])->name('login.post');

/*Route::get('/administration', function () {
    if (!session('admin')) {
        return redirect('/login')->withErrors(['auth' => 'Accès refusé.']);
    }

    return view('admin.administration');
})->name('dashboard');*/

Route::get('/administration', [AdminController::class, 'selectBeneficiaires'])->name('administration');


Route::get('/beneficiaires', [BeneficiaireController::class, 'index'])->name('beneficiaire.index');
// Actions SQL
Route::post('/beneficiaire/update/{id}', [BeneficiaireController::class, 'updateSql'])->name('beneficiaire.updateSql');
// Keep existing POST-delete for compatibility, but prefer DELETE below
Route::post('/beneficiaire/delete/{id}', [BeneficiaireController::class, 'deleteSql'])->name('beneficiaire.deleteSql');
Route::delete('/beneficiaire/{id}', [BeneficiaireController::class, 'destroy'])->name('beneficiaire.destroy');