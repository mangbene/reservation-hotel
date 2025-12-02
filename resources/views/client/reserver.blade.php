@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1><i class="bi bi-calendar-plus"></i> Réserver une chambre</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('client.dashboard') }}">Tableau de bord</a></li>
                    <li class="breadcrumb-item active">Nouvelle réservation</li>
                </ol>
            </nav>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong><i class="bi bi-exclamation-triangle"></i> Erreurs :</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Formulaire de réservation -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Détails de la réservation</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('client.reserver.store') }}" method="POST" id="reservationForm">
                        @csrf
                        
                        <!-- Dates -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="date_arrivee" class="form-label fw-bold">
                                    <i class="bi bi-calendar-event"></i> Date d'arrivée <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       class="form-control form-control-lg @error('date_arrivee') is-invalid @enderror" 
                                       id="date_arrivee" 
                                       name="date_arrivee" 
                                       value="{{ old('date_arrivee') }}"
                                       min="{{ date('Y-m-d') }}"
                                       required>
                                @error('date_arrivee')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="date_depart" class="form-label fw-bold">
                                    <i class="bi bi-calendar-x"></i> Date de départ <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       class="form-control form-control-lg @error('date_depart') is-invalid @enderror" 
                                       id="date_depart" 
                                       name="date_depart" 
                                       value="{{ old('date_depart') }}"
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                       required>
                                @error('date_depart')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Sélection de la chambre -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-door-open"></i> Choisir une chambre <span class="text-danger">*</span>
                            </label>
                            <div class="row g-3">
                                @forelse($chambres as $chambre)
                                <div class="col-md-6">
                                    <input type="radio" 
                                           class="btn-check" 
                                           name="chambre_id" 
                                           id="chambre{{ $chambre->id }}" 
                                           value="{{ $chambre->id }}"
                                           data-prix="{{ $chambre->prix }}"
                                           {{ old('chambre_id') == $chambre->id ? 'checked' : '' }}
                                           required>
                                    <label class="btn btn-outline-primary w-100 text-start p-3 h-100" for="chambre{{ $chambre->id }}" style="min-height: 150px;">
                                        <div class="d-flex flex-column justify-content-between h-100">
                                            <div>
                                                <h6 class="mb-2">
                                                    <i class="bi bi-door-closed"></i> Chambre {{ $chambre->numero }}
                                                </h6>
                                                <p class="mb-1"><strong>Type :</strong> {{ ucfirst($chambre->type) }}</p>
                                                <p class="mb-1"><strong>Capacité :</strong> {{ $chambre->capacite }} personne(s)</p>
                                                @if($chambre->description)
                                                    <small class="text-muted d-block">{{ Str::limit($chambre->description, 50) }}</small>
                                                @endif
                                            </div>
                                            <div class="text-end mt-2">
                                                <h5 class="text-primary mb-0 prix-chambre">{{ number_format($chambre->prix, 0) }} FCFA</h5>
                                                <small class="text-muted">par nuit</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                @empty
                                <div class="col-12">
                                    <div class="alert alert-warning">
                                        <i class="bi bi-exclamation-triangle"></i> Aucune chambre disponible pour le moment.
                                        <a href="{{ route('client.dashboard') }}" class="alert-link">Retour au tableau de bord</a>
                                    </div>
                                </div>
                                @endforelse
                            </div>
                            @error('chambre_id')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($chambres->count() > 0)
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle"></i> Confirmer la réservation
                            </button>
                            <a href="{{ route('client.dashboard') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Retour
                            </a>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <!-- Résumé de la réservation -->
        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-receipt"></i> Résumé</h5>
                </div>
                <div class="card-body">
                    <div id="recapitulatif">
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-info-circle" style="font-size: 2rem;"></i>
                            <p class="mt-3 mb-0">Sélectionnez une chambre et des dates pour voir le résumé</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Styles pour les boutons radio de chambre */
.btn-check {
    position: absolute;
    clip: rect(0,0,0,0);
    pointer-events: none;
}

.btn-check:checked + label {
    background-color: #0d6efd !important;
    color: white !important;
    border-color: #0d6efd !important;
}

.btn-check:checked + label .text-primary,
.btn-check:checked + label .prix-chambre {
    color: white !important;
}

.btn-check:checked + label .text-muted {
    color: rgba(255, 255, 255, 0.8) !important;
}

.btn-outline-primary:hover {
    background-color: #0d6efd;
    color: white;
    border-color: #0d6efd;
}

.btn-outline-primary:hover .text-primary,
.btn-outline-primary:hover .prix-chambre {
    color: white !important;
}

.btn-outline-primary:hover .text-muted {
    color: rgba(255, 255, 255, 0.8) !important;
}

/* Carte collante */
.sticky-top {
    position: -webkit-sticky;
    position: sticky;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateArrivee = document.getElementById('date_arrivee');
    const dateDepart = document.getElementById('date_depart');
    const chambresRadios = document.querySelectorAll('input[name="chambre_id"]');

    function calculerTotal() {
        const chambreSelected = document.querySelector('input[name="chambre_id"]:checked');
        const arrivee = dateArrivee.value;
        const depart = dateDepart.value;
        
        if (chambreSelected && arrivee && depart) {
            const prixParNuit = parseFloat(chambreSelected.dataset.prix);
            const debut = new Date(arrivee);
            const fin = new Date(depart);
            const nuits = Math.ceil((fin - debut) / (1000 * 60 * 60 * 24));
            
            if (nuits > 0) {
                const total = prixParNuit * nuits;
                const chambreLabel = chambreSelected.closest('.col-md-6').querySelector('h6').textContent;
                
                document.getElementById('recapitulatif').innerHTML = `
                    <div class="mb-3">
                        <small class="text-muted">Chambre sélectionnée</small>
                        <h6>${chambreLabel}</h6>
                    </div>
                    <hr>
                    <div class="mb-2 d-flex justify-content-between">
                        <span>Prix par nuit</span>
                        <strong>${prixParNuit.toLocaleString()} FCFA</strong>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span>Nombre de nuits</span>
                        <strong>${nuits}</strong>
                    </div>
                    <hr>
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <span class="h6 mb-0">Total</span>
                        <h4 class="text-primary mb-0">${total.toLocaleString()} FCFA</h4>
                    </div>
                    <div class="alert alert-info mb-0">
                        <small><i class="bi bi-info-circle"></i> La réservation sera confirmée par un administrateur.</small>
                    </div>
                `;
            } else {
                document.getElementById('recapitulatif').innerHTML = `
                    <div class="alert alert-warning mb-0">
                        <i class="bi bi-exclamation-triangle"></i> La date de départ doit être après la date d'arrivée.
                    </div>
                `;
            }
        }
    }

    // Mettre à jour la date minimale de départ quand arrivée change
    dateArrivee.addEventListener('change', function() {
        const dateMin = new Date(this.value);
        dateMin.setDate(dateMin.getDate() + 1);
        dateDepart.min = dateMin.toISOString().split('T')[0];
        calculerTotal();
    });

    dateDepart.addEventListener('change', calculerTotal);
    
    chambresRadios.forEach(radio => {
        radio.addEventListener('change', calculerTotal);
    });

    // Calculer au chargement si des valeurs existent
    if (document.querySelector('input[name="chambre_id"]:checked')) {
        calculerTotal();
    }
});
</script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
@endsection


