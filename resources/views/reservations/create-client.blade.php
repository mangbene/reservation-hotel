@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-calendar-plus"></i> Réserver une chambre
                        @if($client)
                            pour {{ $client->nom }}
                        @endif
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

                    <form action="{{ route('reservations.storeForClient') }}" method="POST">
                        @csrf

                        <!-- Client (caché si fourni) -->
                        @if($client)
                            <input type="hidden" name="client_id" value="{{ $client->id }}">
                            <div class="alert alert-info">
                                <i class="bi bi-person-circle"></i> 
                                Réservation pour : <strong>{{ $client->nom }}</strong> ({{ $client->email }})
                            </div>
                        @else
                            <div class="mb-3">
                                <label for="client_id" class="form-label">
                                    <i class="bi bi-person"></i> Client <span class="text-danger">*</span>
                                </label>
                                <input type="number" name="client_id" id="client_id" class="form-control" required>
                                <div class="form-text">Entrez votre ID client</div>
                            </div>
                        @endif

                        <!-- Chambre -->
                        <div class="mb-3">
                            <label for="chambre_id" class="form-label">
                                <i class="bi bi-door-open"></i> Chambre <span class="text-danger">*</span>
                            </label>
                            <select 
                                name="chambre_id" 
                                id="chambre_id" 
                                class="form-select @error('chambre_id') is-invalid @enderror" 
                                required
                            >
                                <option value="">-- Choisir une chambre --</option>
                                @foreach($chambres as $chambre)
                                    <option value="{{ $chambre->id }}" {{ old('chambre_id') == $chambre->id ? 'selected' : '' }}>
                                        Chambre {{ $chambre->numero }} - {{ ucfirst($chambre->type) }} 
                                        ({{ $chambre->capacite }} pers.) - {{ number_format($chambre->prix, 2) }} € / nuit
                                    </option>
                                @endforeach
                            </select>
                            @error('chambre_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <!-- Date d'arrivée -->
                            <div class="col-md-6 mb-3">
                                <label for="date_arrivee" class="form-label">
                                    <i class="bi bi-calendar-check"></i> Date d'arrivée <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="date" 
                                    name="date_arrivee" 
                                    id="date_arrivee" 
                                    class="form-control @error('date_arrivee') is-invalid @enderror" 
                                    value="{{ old('date_arrivee', date('Y-m-d')) }}" 
                                    min="{{ date('Y-m-d') }}"
                                    required
                                >
                                @error('date_arrivee')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date de départ -->
                            <div class="col-md-6 mb-3">
                                <label for="date_depart" class="form-label">
                                    <i class="bi bi-calendar-x"></i> Date de départ <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="date" 
                                    name="date_depart" 
                                    id="date_depart" 
                                    class="form-control @error('date_depart') is-invalid @enderror" 
                                    value="{{ old('date_depart', date('Y-m-d', strtotime('+1 day'))) }}" 
                                    min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                    required
                                >
                                @error('date_depart')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Information sur le prix -->
                        <div class="alert alert-secondary">
                            <i class="bi bi-info-circle"></i> 
                            Le prix total sera calculé automatiquement en fonction du nombre de nuits et du tarif de la chambre.
                        </div>

                        <!-- Boutons -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('home') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-info text-white">
                                <i class="bi bi-calendar-check"></i> Réserver
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Chambres disponibles -->
            @if($chambres->count() > 0)
                <div class="card shadow mt-4">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-list-ul"></i> Chambres disponibles
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($chambres as $chambre)
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                <i class="bi bi-door-open"></i> Chambre {{ $chambre->numero }}
                                            </h5>
                                            <p class="card-text">
                                                <strong>Type :</strong> {{ ucfirst($chambre->type) }}<br>
                                                <strong>Capacité :</strong> {{ $chambre->capacite }} personne(s)<br>
                                                <strong>Prix :</strong> <span class="text-success fw-bold">{{ number_format($chambre->prix, 2) }} € / nuit</span>
                                            </p>
                                            @if($chambre->description)
                                                <p class="card-text text-muted">
                                                    <small>{{ $chambre->description }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-warning mt-4">
                    <i class="bi bi-exclamation-triangle"></i> 
                    Aucune chambre disponible pour le moment. Veuillez réessayer plus tard.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection