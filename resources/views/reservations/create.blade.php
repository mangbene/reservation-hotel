@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="bi bi-calendar-plus"></i> Créer une réservation
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

                    <!-- Vérifier s'il y a des clients et des chambres -->
                    @if($clients->count() == 0)
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle"></i> 
                            <strong>Aucun client disponible !</strong> 
                            <p class="mb-0">Vous devez d'abord créer au moins un client.</p>
                            <a href="{{ route('clients.create') }}" class="btn btn-sm btn-success mt-2">
                                <i class="bi bi-person-plus"></i> Créer un client
                            </a>
                        </div>
                    @elseif($chambres->count() == 0)
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle"></i> 
                            <strong>Aucune chambre disponible !</strong> 
                            <p class="mb-0">Vous devez d'abord créer au moins une chambre disponible.</p>
                            <a href="{{ route('chambres.create') }}" class="btn btn-sm btn-primary mt-2">
                                <i class="bi bi-plus-circle"></i> Créer une chambre
                            </a>
                        </div>
                    @else
                        <form action="{{ route('reservations.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <!-- Client -->
                                <div class="col-md-6 mb-3">
                                    <label for="client_id" class="form-label">
                                        <i class="bi bi-person-circle"></i> Client <span class="text-danger">*</span>
                                    </label>
                                    <select 
                                        name="client_id" 
                                        id="client_id" 
                                        class="form-select @error('client_id') is-invalid @enderror" 
                                        required
                                    >
                                        <option value="">-- Sélectionner un client --</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                                {{ $client->nom }} ({{ $client->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('client_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <small>
                                            <i class="bi bi-info-circle"></i> 
                                            {{ $clients->count() }} client(s) disponible(s)
                                            | <a href="{{ route('clients.create') }}">Créer un nouveau client</a>
                                        </small>
                                    </div>
                                </div>

                                <!-- Chambre -->
                                <div class="col-md-6 mb-3">
                                    <label for="chambre_id" class="form-label">
                                        <i class="bi bi-door-open"></i> Chambre <span class="text-danger">*</span>
                                    </label>
                                    <select 
                                        name="chambre_id" 
                                        id="chambre_id" 
                                        class="form-select @error('chambre_id') is-invalid @enderror" 
                                        required
                                    >
                                        <option value="">-- Sélectionner une chambre --</option>
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
                                    <div class="form-text">
                                        <small>
                                            <i class="bi bi-info-circle"></i> 
                                            {{ $chambres->count() }} chambre(s) disponible(s)
                                            | <a href="{{ route('chambres.create') }}">Créer une nouvelle chambre</a>
                                        </small>
                                    </div>
                                </div>
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

                            <!-- Calculateur de prix (JavaScript) -->
                            <div class="alert alert-info" id="prix-info">
                                <i class="bi bi-calculator"></i> 
                                <strong>Calcul du prix :</strong> 
                                <span id="prix-details">Sélectionnez une chambre et des dates pour voir le prix</span>
                            </div>

                            <!-- Boutons -->
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('reservations.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Retour
                                </a>
                                <button type="submit" class="btn btn-warning text-dark">
                                    <i class="bi bi-save"></i> Créer la réservation
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Liste des clients disponibles -->
            @if($clients->count() > 0)
                <div class="card shadow mt-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-people"></i> Clients disponibles ({{ $clients->count() }})
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Téléphone</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($clients as $client)
                                    <tr>
                                        <td><strong>{{ $client->id }}</strong></td>
                                        <td>{{ $client->nom }}</td>
                                        <td>{{ $client->email }}</td>
                                        <td>{{ $client->telephone ?? 'N/A' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Liste des chambres disponibles -->
            @if($chambres->count() > 0)
                <div class="card shadow mt-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-door-open"></i> Chambres disponibles ({{ $chambres->count() }})
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($chambres as $chambre)
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                Chambre {{ $chambre->numero }}
                                            </h6>
                                            <p class="card-text mb-1">
                                                <small>
                                                    <strong>Type :</strong> {{ ucfirst($chambre->type) }}<br>
                                                    <strong>Capacité :</strong> {{ $chambre->capacite }} pers.<br>
                                                    <strong>Prix :</strong> <span class="text-success">{{ number_format($chambre->prix, 2) }} €/nuit</span>
                                                </small>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
// Calculateur de prix dynamique
document.addEventListener('DOMContentLoaded', function() {
    const chambreSelect = document.getElementById('chambre_id');
    const dateArrivee = document.getElementById('date_arrivee');
    const dateDepart = document.getElementById('date_depart');
    const prixDetails = document.getElementById('prix-details');

    function calculerPrix() {
        if (chambreSelect.value && dateArrivee.value && dateDepart.value) {
            const chambreOption = chambreSelect.options[chambreSelect.selectedIndex];
            const prixText = chambreOption.text;
            const prixMatch = prixText.match(/(\d+\.?\d*) €/);
            
            if (prixMatch) {
                const prixNuit = parseFloat(prixMatch[1]);
                const debut = new Date(dateArrivee.value);
                const fin = new Date(dateDepart.value);
                const diffTime = Math.abs(fin - debut);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                
                if (diffDays > 0) {
                    const total = prixNuit * diffDays;
                    prixDetails.innerHTML = `<strong>${diffDays} nuit(s)</strong> × <strong>${prixNuit.toFixed(2)} €</strong> = <strong class="text-success">${total.toFixed(2)} €</strong>`;
                } else {
                    prixDetails.innerHTML = 'La date de départ doit être après la date d\'arrivée';
                }
            }
        }
    }

    if (chambreSelect && dateArrivee && dateDepart) {
        chambreSelect.addEventListener('change', calculerPrix);
        dateArrivee.addEventListener('change', calculerPrix);
        dateDepart.addEventListener('change', calculerPrix);
        
        // Calculer au chargement si des valeurs sont présentes
        calculerPrix();
    }
});
</script>
@endsection