@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1 class="mb-2"><i class="bi bi-house-door"></i> Mon Espace Client</h1>
            <p class="text-muted">Bienvenue, <strong>{{ Auth::user()->name }}</strong></p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Actions principales -->
    <div class="row mb-5 g-4">
        <div class="col-md-6">
            <div class="card h-100 shadow">
                <div class="card-body text-center py-5">
                    <i class="bi bi-calendar-plus text-primary" style="font-size: 4rem;"></i>
                    <h3 class="mt-3">Nouvelle Réservation</h3>
                    <p class="text-muted">Réservez votre chambre facilement</p>
                    <a href="{{ route('client.reserver') }}" class="btn btn-primary btn-lg">
                        Réserver maintenant
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100 shadow">
                <div class="card-body text-center py-5">
                    <i class="bi bi-list-check text-success" style="font-size: 4rem;"></i>
                    <h3 class="mt-3">Mes Réservations</h3>
                    <p class="text-muted">Consultez votre historique</p>
                    <a href="{{ route('client.reservations') }}" class="btn btn-success btn-lg">
                        Voir mes réservations
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4 g-3">
        <div class="col-md-4">
            <div class="card text-white bg-info">
                <div class="card-body text-center">
                    <h2>{{ Auth::user()->reservations()->count() }}</h2>
                    <p class="mb-0">Total réservations</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body text-center">
                    <h2>{{ Auth::user()->reservations()->whereIn('statut', ['confirme', 'attente'])->count() }}</h2>
                    <p class="mb-0">Réservations actives</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning">
                <div class="card-body text-center">
                    <h2>{{ Auth::user()->reservations()->where('statut', 'attente')->count() }}</h2>
                    <p class="mb-0">En attente</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Réservations actives -->
    <div class="row">
        <div class="col-12 mb-3">
            <h3><i class="bi bi-calendar-event"></i> Mes réservations actives</h3>
        </div>

        @php
            $actives = Auth::user()->reservations()
                ->with('chambre')
                ->whereIn('statut', ['confirme', 'attente'])
                ->where('date_depart', '>=', now())
                ->latest()
                ->take(3)
                ->get();
        @endphp

        @forelse($actives as $reservation)
        <div class="col-12 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5>
                                Chambre {{ $reservation->chambre->numero }} - {{ ucfirst($reservation->chambre->type) }}
                                <span class="badge bg-{{ $reservation->statut === 'confirme' ? 'success' : 'warning' }}">
                                    {{ ucfirst($reservation->statut) }}
                                </span>
                            </h5>
                            <p class="mb-0">
                                <i class="bi bi-calendar"></i>
                                Du {{ \Carbon\Carbon::parse($reservation->date_arrivee)->format('d/m/Y') }}
                                au {{ \Carbon\Carbon::parse($reservation->date_depart)->format('d/m/Y') }}
                            </p>
                        </div>
                        <div class="col-md-2 text-center">
                            <h4 class="text-primary">{{ number_format($reservation->prix_total, 0) }} FCFA</h4>
                        </div>
                        <div class="col-md-2 text-end">
                            @if($reservation->statut !== 'annule')
                            <form action="{{ route('client.reservations.annuler', $reservation->id) }}" method="POST" onsubmit="return confirm('Annuler cette réservation ?')">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger">Annuler</button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center py-5">
                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                <h4 class="mt-3">Aucune réservation active</h4>
                <a href="{{ route('client.reserver') }}" class="btn btn-primary mt-3">Faire une réservation</a>
            </div>
        </div>
        @endforelse
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
@endsection