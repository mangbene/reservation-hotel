@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-person-plus"></i> Ajouter un nouveau client
                    </h4>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Erreurs détectées :</strong>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('clients.store') }}" method="POST">
                        @csrf

                        <!-- Nom -->
                        <div class="mb-3">
                            <label for="nom" class="form-label">
                                <i class="bi bi-person"></i> Nom complet <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="nom" 
                                id="nom" 
                                class="form-control @error('nom') is-invalid @enderror" 
                                value="{{ old('nom') }}" 
                                required
                                placeholder="Ex: Jean Dupont"
                                autofocus
                            >
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope"></i> Adresse email <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="email" 
                                name="email" 
                                id="email" 
                                class="form-control @error('email') is-invalid @enderror" 
                                value="{{ old('email') }}" 
                                required
                                placeholder="Ex: client@exemple.com"
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Mot de passe -->
                        <div class="mb-3">
                            <label for="mot_de_passe" class="form-label">
                                <i class="bi bi-lock"></i> Mot de passe <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="password" 
                                name="mot_de_passe" 
                                id="mot_de_passe" 
                                class="form-control @error('mot_de_passe') is-invalid @enderror" 
                                required
                                minlength="6"
                                placeholder="Minimum 6 caractères"
                            >
                            @error('mot_de_passe')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="bi bi-info-circle"></i> Le mot de passe doit contenir au moins 6 caractères
                            </div>
                        </div>

                        <!-- Téléphone -->
                        <div class="mb-3">
                            <label for="telephone" class="form-label">
                                <i class="bi bi-telephone"></i> Téléphone
                            </label>
                            <input 
                                type="text" 
                                name="telephone" 
                                id="telephone" 
                                class="form-control @error('telephone') is-invalid @enderror" 
                                value="{{ old('telephone') }}"
                                placeholder="Ex: +33 6 12 34 56 78"
                            >
                            @error('telephone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Optionnel</div>
                        </div>

                        <!-- Boutons -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('clients.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection