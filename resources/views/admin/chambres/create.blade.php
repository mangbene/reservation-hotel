@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1><i class="bi bi-plus-circle"></i> Ajouter une chambre</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.chambres.index') }}">Chambres</a></li>
                    <li class="breadcrumb-item active">Ajouter</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-door-open"></i> Informations de la chambre</h5>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <strong>Erreurs :</strong>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.chambres.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="numero" class="form-label">
                                    Numéro de chambre <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('numero') is-invalid @enderror" 
                                       id="numero" 
                                       name="numero" 
                                       value="{{ old('numero') }}" 
                                       placeholder="Ex: 101"
                                       required>
                                @error('numero')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">
                                    Type de chambre <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('type') is-invalid @enderror" 
                                        id="type" 
                                        name="type" 
                                        required>
                                    <option value="">-- Choisir --</option>
                                    <option value="simple" {{ old('type') === 'simple' ? 'selected' : '' }}>Simple</option>
                                    <option value="double" {{ old('type') === 'double' ? 'selected' : '' }}>Double</option>
                                    <option value="suite" {{ old('type') === 'suite' ? 'selected' : '' }}>Suite</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="capacite" class="form-label">
                                    Capacité (personnes) <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control @error('capacite') is-invalid @enderror" 
                                       id="capacite" 
                                       name="capacite" 
                                       value="{{ old('capacite') }}" 
                                       min="1"
                                       placeholder="Ex: 2"
                                       required>
                                @error('capacite')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="prix" class="form-label">
                                    Prix par nuit (FCFA) <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control @error('prix') is-invalid @enderror" 
                                       id="prix" 
                                       name="prix" 
                                       value="{{ old('prix') }}" 
                                       min="0"
                                       step="1"
                                       placeholder="Ex: 25000"
                                       required>
                                @error('prix')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="statut" class="form-label">
                                Statut <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('statut') is-invalid @enderror" 
                                    id="statut" 
                                    name="statut" 
                                    required>
                                <option value="disponible" {{ old('statut', 'disponible') === 'disponible' ? 'selected' : '' }}>Disponible</option>
                                <option value="occupee" {{ old('statut') === 'occupee' ? 'selected' : '' }}>Occupée</option>
                                <option value="maintenance" {{ old('statut') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>
                            @error('statut')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4"
                                      placeholder="Description de la chambre, équipements, etc.">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Créer la chambre
                            </button>
                            <a href="{{ route('admin.chambres.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
@endsection