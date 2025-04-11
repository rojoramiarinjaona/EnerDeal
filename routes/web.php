<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\VendeurController;
use App\Http\Controllers\Dashboard\ClientController;
use App\Http\Controllers\EnergieController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Routes publiques
Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return redirect()->route('dashboard');
})->name('home');

Route::get('/energies', [EnergieController::class, 'index'])->name('energies.index');
Route::get('/energies/{energie:slug}', [EnergieController::class, 'show'])->name('energies.show');

// Routes d'authentification (fournies par Breeze)
require __DIR__.'/auth.php';

// Routes protégées (nécessitant authentification)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Routes pour les clients
    Route::middleware(['role:client'])->prefix('client')->name('client.')->group(function () {
        Route::get('/dashboard', [ClientController::class, 'dashboard'])->name('dashboard');
        
        // Nouvelles routes pour le profil client
        Route::get('/profile', [ClientController::class, 'profile'])->name('profile');
        Route::put('/profile', [ClientController::class, 'updateProfile'])->name('profile.update');
        Route::get('/change-password', [ClientController::class, 'changePassword'])->name('change-password');
        Route::put('/change-password', [ClientController::class, 'updatePassword'])->name('update-password');
        
        // Recherche de formules
        Route::get('/search-formules', [ClientController::class, 'searchFormules'])->name('search-formules');
    });
    
    // Routes pour les vendeurs
    Route::middleware(['role:vendeur'])->prefix('vendeur')->group(function () {
        Route::get('/dashboard', [VendeurController::class, 'dashboard'])->name('vendeur.dashboard');
        
       
        
       
        
        // Nouvelles routes pour le profil vendeur
        Route::get('/profile', [VendeurController::class, 'profile'])->name('vendeur.profile');
        Route::put('/profile', [VendeurController::class, 'updateProfile'])->name('vendeur.profile.update');
        Route::get('/change-password', [VendeurController::class, 'changePassword'])->name('vendeur.change-password');
        Route::put('/change-password', [VendeurController::class, 'updatePassword'])->name('vendeur.update-password');
        
       
    });
    
    // Routes pour les admins
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
         // Gestion des vendeurs
         Route::get('/vendeurs', [AdminController::class, 'vendeurs'])->name('vendeurs.index');
         Route::get('/vendeurs/{user}', [AdminController::class, 'showVendeur'])->name('vendeurs.show');
        
       
        
        // Gestion des clients
        Route::get('/clients', [AdminController::class, 'index'])->name('clients.index');
        Route::get('/clients/{user}', [AdminController::class, 'show'])->name('clients.show');
        
        // Autres routes admin
       
        
        // Profil admin
        Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
        Route::put('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');
        Route::get('/change-password', [AdminController::class, 'changePassword'])->name('change-password');
        Route::put('/change-password', [AdminController::class, 'updatePassword'])->name('update-password');
        
      
    });
});
