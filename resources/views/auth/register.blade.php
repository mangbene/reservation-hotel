@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-7">
            <div class="card shadow-lg">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold">Créer un compte</h2>
                        <p class="text-muted">Rejoignez l'Hôtel Azure</p>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <strong><i class="bi bi-exclamation-triangle"></i> Erreurs :</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        
                        <!-- Nom complet -->
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <i class="bi bi-person"></i> Nom complet <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   placeholder="Jean Dupont"
                                   required 
                                   autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email et Téléphone -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope"></i> Email <span class="text-danger">*</span>
                                </label>
                                <input type="email" 
                                       class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       placeholder="exemple@email.com"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="telephone" class="form-label">
                                    <i class="bi bi-telephone"></i> Téléphone
                                </label>
                                <input type="tel" 
                                       class="form-control form-control-lg" 
                                       id="telephone" 
                                       name="telephone" 
                                       value="{{ old('telephone') }}" 
                                       placeholder="+228 90 00 00 00">
                            </div>
                        </div>

                        <!-- Mots de passe -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock"></i> Mot de passe <span class="text-danger">*</span>
                                </label>
                                <input type="password" 
                                       class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Min. 6 caractères"
                                       required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Minimum 6 caractères</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">
                                    <i class="bi bi-lock-fill"></i> Confirmer <span class="text-danger">*</span>
                                </label>
                                <input type="password" 
                                       class="form-control form-control-lg" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       placeholder="Même mot de passe"
                                       required>
                            </div>
                        </div>

                        <!-- Choix du type de compte -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="bi bi-person-badge"></i> Type de compte <span class="text-danger">*</span>
                            </label>
                            <div class="row g-3">
                                <!-- Option Client -->
                                <div class="col-md-6">
                                    <input type="radio" 
                                           class="btn-check" 
                                           name="role" 
                                           id="role-client" 
                                           value="client" 
                                           {{ old('role', 'client') === 'client' ? 'checked' : '' }}
                                           required>
                                    <label class="btn btn-outline-primary w-100 py-3" for="role-client">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="bi bi-person-circle" style="font-size: 2.5rem;"></i>
                                            <h5 class="mt-2 mb-1">Client</h5>
                                            <small class="text-muted">Réserver des chambres</small>
                                        </div>
                                    </label>
                                </div>

                                <!-- Option Admin -->
                                <div class="col-md-6">
                                    <input type="radio" 
                                           class="btn-check" 
                                           name="role" 
                                           id="role-admin" 
                                           value="admin"
                                           {{ old('role') === 'admin' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-danger w-100 py-3" for="role-admin">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="bi bi-shield-check" style="font-size: 2.5rem;"></i>
                                            <h5 class="mt-2 mb-1">Administrateur</h5>
                                            <small class="text-muted">Gérer l'hôtel</small>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            @error('role')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Bouton Submit -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-person-plus"></i> Créer mon compte
                            </button>
                        </div>
                    </form>

                    <!-- Lien vers connexion -->
                    <hr class="my-4">

                    <div class="text-center">
                        <p class="mb-0">Vous avez déjà un compte ?</p>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary mt-2">
                            <i class="bi bi-box-arrow-in-right"></i> Se connecter
                        </a>
                    </div>

                    <div class="text-center mt-3">
                        <a href="{{ route('home') }}" class="text-muted">
                            <i class="bi bi-arrow-left"></i> Retour à l'accueil
                        </a>
                    </div>
                </div>
            </div>

            <!-- Info pour dev -->
            <div class="alert alert-info mt-3">
                <small>
                    <i class="bi bi-info-circle"></i> 
                    <strong>Pour tester :</strong> Choisissez "Client" pour accéder à l'espace client, 
                    ou "Administrateur" pour accéder au dashboard admin.
                </small>
            </div>
        </div>
    </div>
</div>

<style>
.btn-check:checked + label {
    font-weight: bold;
    transform: scale(1.02);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.btn-outline-primary:hover,
.btn-outline-danger:hover {
    transform: translateY(-2px);
    transition: all 0.3s ease;
}
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
@endsection