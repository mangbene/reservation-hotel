<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ChambresController,
    ClientsController,
    ReservationsController,
    AdminsController,
    AuthController
};

// Page d'accueil
Route::get('/', function () {
    return view('home');
})->name('home');

// Authentification (inscription / connexion / déconnexion)
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Routes protégées par auth
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function() {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('chambres', ChambresController::class);
    Route::resource('clients', ClientsController::class);
    Route::resource('reservations', ReservationsController::class);
    Route::resource('admins', AdminsController::class);
});
Route::get('/', [AuthController::class, 'showHome'])->name('home');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');