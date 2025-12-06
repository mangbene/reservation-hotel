@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="bi bi-pencil"></i> Modifier la chambre #{{ $chambre->numero }}
                    </h4>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Erreurs :</strong>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('chambres.update', $chambre->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Numéro de chambre -->
                            <div class="col-md-6 mb-3">
                                <label for="numero" class="form-label">
                                    <i class="bi bi-hash"></i> Numéro de chambre <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="numero" 
                                    id="numero" 
                                    class="form-control @error('numero') is-invalid @enderror" 
                                    value="{{ old('numero', $chambre->numero) }}" 
                                    required
                                >
                                @error('numero')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Type de chambre -->
                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">
                                    <i class="bi bi-house-door"></i> Type de chambre <span class="text-danger">*</span>
                                </label>
                                <select 
                                    name="type" 
                                    id="type" 
                                    class="form-select @error('type') is-invalid @enderror" 
                                    required
                                >
                                    <option value="">-- Choisir un type --</option>
                                    <option value="simple" {{ old('type', $chambre->type) == 'simple' ? 'selected' : '' }}>Simple</option>
                                    <option value="double" {{ old('type', $chambre->type) == 'double' ? 'selected' : '' }}>Double</option>
                                    <option value="suite" {{ old('type', $chambre->type) == 'suite' ? 'selected' : '' }}>Suite</option>
                                    <option value="familiale" {{ old('type', $chambre->type) == 'familiale' ? 'selected' : '' }}>Familiale</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Capacité -->
                            <div class="col-md-6 mb-3">
                                <label for="capacite" class="form-label">
                                    <i class="bi bi-people"></i> Capacité (personnes) <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="number" 
                                    name="capacite" 
                                    id="capacite" 
                                    class="form-control @error('capacite') is-invalid @enderror" 
                                    value="{{ old('capacite', $chambre->capacite) }}" 
                                    min="1" 
                                    max="10"
                                    required
                                >
                                @error('capacite')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Prix par nuit -->
                            <div class="col-md-6 mb-3">
                                <label for="prix" class="form-label">
                                    <i class="bi bi-currency-euro"></i> Prix par nuit (€) <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="number" 
                                    name="prix" 
                                    id="prix" 
                                    class="form-control @error('prix') is-invalid @enderror" 
                                    value="{{ old('prix', $chambre->prix) }}" 
                                    step="0.01" 
                                    min="0"
                                    required
                                >
                                @error('prix')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Statut -->
                        <div class="mb-3">
                            <label for="statut" class="form-label">
                                <i class="bi bi-check-circle"></i> Statut <span class="text-danger">*</span>
                            </label>
                            <select 
                                name="statut" 
                                id="statut" 
                                class="form-select @error('statut') is-invalid @enderror" 
                                required
                            >
                                <option value="disponible" {{ old('statut', $chambre->statut) == 'disponible' ? 'selected' : '' }}>Disponible</option>
                                <option value="occupee" {{ old('statut', $chambre->statut) == 'occupee' ? 'selected' : '' }}>Occupée</option>
                                <option value="maintenance" {{ old('statut', $chambre->statut) == 'maintenance' ? 'selected' : '' }}>En maintenance</option>
                            </select>
                            @error('statut')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">
                                <i class="bi bi-card-text"></i> Description
                            </label>
                            <textarea 
                                name="description" 
                                id="description" 
                                class="form-control @error('description') is-invalid @enderror" 
                                rows="4"
                            >{{ old('description', $chambre->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Optionnel - Décrivez les équipements et caractéristiques de la chambre</div>
                        </div>

                        <!-- Informations supplémentaires -->
                        <div class="alert alert-info">
                            <small>
                                <i class="bi bi-info-circle"></i> 
                                Créée le : {{ $chambre->created_at->format('d/m/Y à H:i') }}
                                @if($chambre->updated_at != $chambre->created_at)
                                    | Dernière modification : {{ $chambre->updated_at->format('d/m/Y à H:i') }}
                                @endif
                            </small>
                        </div>

                        <!-- Boutons -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('chambres.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-warning">
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