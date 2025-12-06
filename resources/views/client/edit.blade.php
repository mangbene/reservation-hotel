@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="bi bi-pencil"></i> Modifier le client : {{ $client->nom }}
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

                    <form action="{{ route('clients.update', $client->id) }}" method="POST">
                        @csrf
                        @method('PUT')

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
                                value="{{ old('nom', $client->nom) }}" 
                                required
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
                                value="{{ old('email', $client->email) }}" 
                                required
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Mot de passe -->
                        <div class="mb-3">
                            <label for="mot_de_passe" class="form-label">
                                <i class="bi bi-lock"></i> Nouveau mot de passe
                            </label>
                            <input 
                                type="password" 
                                name="mot_de_passe" 
                                id="mot_de_passe" 
                                class="form-control @error('mot_de_passe') is-invalid @enderror" 
                                minlength="6"
                                placeholder="Laisser vide pour conserver l'ancien"
                            >
                            @error('mot_de_passe')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="bi bi-info-circle"></i> 
                                Laissez ce champ vide si vous ne souhaitez pas modifier le mot de passe
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
                                value="{{ old('telephone', $client->telephone) }}"
                            >
                            @error('telephone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Informations supplémentaires -->
                        <div class="alert alert-info">
                            <small>
                                <i class="bi bi-info-circle"></i> 
                                <strong>Client créé le :</strong> {{ $client->created_at->format('d/m/Y à H:i') }}
                                @if($client->updated_at != $client->created_at)
                                    <br>
                                    <strong>Dernière modification :</strong> {{ $client->updated_at->format('d/m/Y à H:i') }}
                                @endif
                            </small>
                        </div>

                        <!-- Boutons -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('clients.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-warning text-dark">
                                <i class="bi bi-save"></i> Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection