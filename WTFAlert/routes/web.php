<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InfoController;
use App\Http\Controllers\HabitantFoyerController;
use App\Http\Controllers\FoyersExportController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/info', [InfoController::class, 'index'])->name('info.index');
Route::get('/info/{id}', [InfoController::class, 'show'])->name('info.show');

// Route pour la liste des habitants et leurs foyers
Route::get('/habitants-foyers', [HabitantFoyerController::class, 'index'])->name('habitants.foyers.index');
/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'admin.role'])->group(function () {
    // Tableau de bord
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Autres routes d'administration seront ajoutées ici

    // Route de déconnexion
    Route::post('/logout', function () {
        auth()->logout();
        return redirect('/');
    })->name('logout');
});

// Routes d'authentification
Route::middleware('guest')->group(function () {
    Route::get('login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);
});

Route::post('logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])
            ->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
});


Route::get('/accueil', function () {    return view('accueil');});
Route::get('/administres', [HabitantFoyerController::class, 'administres'])->name('administres');

// Routes pour l'export des foyers
Route::post('/export/foyers/pdf', [FoyersExportController::class, 'pdf'])->name('foyers.export.pdf');
Route::post('/export/foyers/email', [FoyersExportController::class, 'email'])->name('foyers.export.email');
