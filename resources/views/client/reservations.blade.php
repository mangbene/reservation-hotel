@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1><i class="bi bi-list-check"></i> Mes réservations</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('client.dashboard') }}">Tableau de bord</a></li>
                    <li class="breadcrumb-item active">Mes réservations</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <a href="{{ route('client.reserver') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nouvelle réservation
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-x-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="btn-group w-100" role="group">
                <input type="radio" class="btn-check" name="filter" id="filter-all" autocomplete="off" checked>
                <label class="btn btn-outline-primary" for="filter-all" onclick="filterReservations('all')">
                    <i class="bi bi-list"></i> Toutes
                </label>

                <input type="radio" class="btn-check" name="filter" id="filter-active" autocomplete="off">
                <label class="btn btn-outline-success" for="filter-active" onclick="filterReservations('active')">
                    <i class="bi bi-check-circle"></i> Actives
                </label>

                <input type="radio" class="btn-check" name="filter" id="filter-past" autocomplete="off">
                <label class="btn btn-outline-secondary" for="filter-past" onclick="filterReservations('past')">
                    <i class="bi bi-clock-history"></i> Passées
                </label>

                <input type="radio" class="btn-check" name="filter" id="filter-cancelled" autocomplete="off">
                <label class="btn btn-outline-danger" for="filter-cancelled" onclick="filterReservations('cancelled')">
                    <i class="bi bi-x-circle"></i> Annulées
                </label>
            </div>
        </div>
    </div>

    <!-- Liste des réservations -->
    @forelse($reservations as $reservation)
    <div class="card mb-3 shadow-sm reservation-card" 
         data-statut="{{ $reservation->statut }}"
         data-date="{{ $reservation->date_depart }}">
        <div class="card-body">
            <div class="row align-items-center">
                <!-- Image -->
                <div class="col-md-2">
                    <img src="https://via.placeholder.com/200x150?text=Chambre+{{ $reservation->chambre->numero }}" 
                         class="img-fluid rounded" 
                         alt="Chambre {{ $reservation->chambre->numero }}">
                </div>

                <!-- Détails -->
                <div class="col-md-5">
                    <h5 class="mb-2">
                        <i class="bi bi-door-open"></i>
                        Chambre {{ $reservation->chambre->numero }} - {{ ucfirst($reservation->chambre->type) }}
                    </h5>
                    
                    <!-- Statut -->
                    @if($reservation->statut === 'confirme')
                        <span class="badge bg-success mb-2">
                            <i class="bi bi-check-circle"></i> Confirmée
                        </span>
                    @elseif($reservation->statut === 'attente')
                        <span class="badge bg-warning text-dark mb-2">
                            <i class="bi bi-clock"></i> En attente
                        </span>
                    @else
                        <span class="badge bg-danger mb-2">
                            <i class="bi bi-x-circle"></i> Annulée
                        </span>
                    @endif
                    
                    <p class="mb-1">
                        <i class="bi bi-calendar-event text-primary"></i>
                        <strong>Arrivée :</strong> {{ \Carbon\Carbon::parse($reservation->date_arrivee)->format('d/m/Y') }}
                    </p>
                    <p class="mb-1">
                        <i class="bi bi-calendar-x text-danger"></i>
                        <strong>Départ :</strong> {{ \Carbon\Carbon::parse($reservation->date_depart)->format('d/m/Y') }}
                    </p>
                    <p class="mb-1">
                        <i class="bi bi-moon-stars text-info"></i>
                        <strong>Durée :</strong> 
                        {{ \Carbon\Carbon::parse($reservation->date_arrivee)->diffInDays(\Carbon\Carbon::parse($reservation->date_depart)) }} nuit(s)
                    </p>
                    <small class="text-muted">
                        <i class="bi bi-clock"></i> 
                        Réservée le {{ \Carbon\Carbon::parse($reservation->created_at)->format('d/m/Y à H:i') }}
                    </small>
                </div>

                <!-- Prix -->
                <div class="col-md-3 text-center">
                    <h3 class="text-primary mb-0">{{ number_format($reservation->prix_total, 0, ',', ' ') }}</h3>
                    <small class="text-muted">FCFA</small>
                </div>

                <!-- Actions -->
                <div class="col-md-2 text-end">
                    @php
                        $isPast = \Carbon\Carbon::parse($reservation->date_depart)->isPast();
                        $isFuture = \Carbon\Carbon::parse($reservation->date_arrivee)->isFuture();
                    @endphp

                    @if($reservation->statut !== 'annule' && $isFuture)
                        <form action="{{ route('client.reservations.annuler', $reservation->id) }}" 
                              method="POST" 
                              onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                <i class="bi bi-x-circle"></i> Annuler
                            </button>
                        </form>
                    @elseif($isPast && $reservation->statut === 'confirme')
                        <span class="badge bg-secondary">
                            <i class="bi bi-check"></i> Terminée
                        </span>
                    @elseif($reservation->statut === 'annule')
                        <span class="text-muted">
                            <i class="bi bi-x-circle"></i> Annulée
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="alert alert-info text-center py-5">
        <i class="bi bi-inbox" style="font-size: 3rem;"></i>
        <h4 class="mt-3">Aucune réservation</h4>
        <p class="mb-3">Vous n'avez pas encore effectué de réservation.</p>
        <a href="{{ route('client.reserver') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Faire une réservation
        </a>
    </div>
    @endforelse

    <!-- Pagination -->
    @if($reservations->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $reservations->links() }}
    </div>
    @endif
</div>

<style>
.reservation-card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.reservation-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
</style>

<script>
function filterReservations(filter) {
    const cards = document.querySelectorAll('.reservation-card');
    const today = new Date().toISOString().split('T')[0];
    
    cards.forEach(card => {
        const statut = card.dataset.statut;
        const dateDepart = card.dataset.date;
        
        let show = false;
        
        switch(filter) {
            case 'all':
                show = true;
                break;
            case 'active':
                show = (statut === 'confirme' || statut === 'attente') && dateDepart >= today;
                break;
            case 'past':
                show = dateDepart < today && statut !== 'annule';
                break;
            case 'cancelled':
                show = statut === 'annule';
                break;
        }
        
        card.style.display = show ? 'block' : 'none';
    });
}
</script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
@endsection