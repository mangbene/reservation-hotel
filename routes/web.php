<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ChambresController,
    ClientsController,
    ReservationsController,
    AdminsController,
    AuthController
};

// Page d'accueil publique
Route::get('/', [AuthController::class, 'showHome'])->name('home');

// Authentification
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Dashboard (accessible à tous les utilisateurs connectés)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function() {
        $user = auth()->user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('client.dashboard');
    })->name('dashboard');
});

// Routes ADMIN uniquement
Route::middleware(['auth', 'check.role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function() {
        return view('admin.dashboard');
    })->name('dashboard');
    
    // Gestion des chambres
    Route::resource('chambres', ChambresController::class);
    
    // Gestion des réservations
    Route::resource('reservations', ReservationsController::class);
    
    // Gestion des utilisateurs
    Route::resource('clients', ClientsController::class);
    Route::resource('admins', AdminsController::class);
});

// Routes CLIENT uniquement
Route::middleware(['auth', 'check.role:client'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', function() {
        return view('client.dashboard');
    })->name('dashboard');
    
    // Voir ses propres réservations
    Route::get('/mes-reservations', [ReservationsController::class, 'mesReservations'])->name('reservations');
    
    // Créer une nouvelle réservation
    Route::get('/reserver', [ReservationsController::class, 'createForClient'])->name('reserver');
    Route::post('/reserver', [ReservationsController::class, 'storeForClient'])->name('reserver.store');
    
    // Annuler sa réservation
    Route::post('/reservations/{id}/annuler', [ReservationsController::class, 'annuler'])->name('reservations.annuler');
});

// Routes publiques (accessible sans connexion)
Route::get('/chambres-disponibles', [ChambresController::class, 'disponibles'])->name('chambres.disponibles');
