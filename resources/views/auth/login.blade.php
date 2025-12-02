@extends('layouts.app')

@section('content')
<div class="min-vh-100 d-flex flex-column">
    <!-- Contenu principal centré -->
    <div class="container flex-grow-1 d-flex align-items-center justify-content-center py-5">
        <div class="col-md-5">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <i class="bi bi-building" style="font-size: 3rem; color: #0d6efd;"></i>
                        </div>
                        <h2 class="fw-bold">Connexion</h2>
                        <p class="text-muted">Accédez à votre espace Hôtel Azure</p>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="bi bi-exclamation-triangle"></i>
                            {{ $errors->first() }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="bi bi-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       placeholder="exemple@email.com"
                                       required 
                                       autofocus>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label for="password" class="form-label mb-0">Mot de passe</label>
                                <a href="#" class="text-primary text-decoration-none small" 
                                   onclick="alert('Contactez l\'administrateur pour réinitialiser votre mot de passe.'); return false;">
                                    Mot de passe oublié ?
                                </a>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" 
                                       class="form-control" 
                                       id="password" 
                                       name="password" 
                                       placeholder="••••••••"
                                       required>
                            </div>
                        </div>

                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Se souvenir de moi
                            </label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right"></i> Se connecter
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="mb-0 text-muted">Pas encore de compte ?</p>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary mt-2 w-100">
                            <i class="bi bi-person-plus"></i> Créer un compte
                        </a>
                    </div>

                    <div class="text-center mt-3">
                        <a href="{{ route('home') }}" class="text-muted text-decoration-none small">
                            <i class="bi bi-arrow-left"></i> Retour à l'accueil
                        </a>
                    </div>
                </div>
            </div>

            <!-- Comptes de test -->
            <div class="card mt-3 bg-light border-0">
                <div class="card-body py-2">
                    <small class="text-muted">
                        <i class="bi bi-info-circle"></i> <strong>Comptes de test :</strong><br>
                        Admin: admin@hotelazure.com / password<br>
                        Client: jean@example.com / password
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer en bas -->
    <footer class="bg-dark text-white text-center py-3 mt-auto">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} Hôtel Azure - Projet scolaire</p>
            <small class="text-muted">Réalisé avec Laravel</small>
        </div>
    </footer>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
@endsection