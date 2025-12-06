@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1 class="mb-2"><i class="bi bi-speedometer2"></i> Tableau de bord Administrateur</h1>
            <p class="text-muted">Bienvenue, <strong>{{ Auth::user()->name }}</strong></p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistiques -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary shadow">
                <div class="card-body text-center">
                    <i class="bi bi-door-open" style="font-size: 3rem;"></i>
                    <h2 class="mt-3">{{ \App\Models\Chambre::count() }}</h2>
                    <p class="mb-0">Chambres totales</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-success shadow">
                <div class="card-body text-center">
                    <i class="bi bi-check-circle" style="font-size: 3rem;"></i>
                    <h2 class="mt-3">{{ \App\Models\Chambre::where('statut', 'disponible')->count() }}</h2>
                    <p class="mb-0">Disponibles</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-warning shadow">
                <div class="card-body text-center">
                    <i class="bi bi-calendar-check" style="font-size: 3rem;"></i>
                    <h2 class="mt-3">{{ \App\Models\Reservation::whereIn('statut', ['confirme', 'attente'])->count() }}</h2>
                    <p class="mb-0">Réservations actives</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-info shadow">
                <div class="card-body text-center">
                    <i class="bi bi-people" style="font-size: 3rem;"></i>
                    <h2 class="mt-3">{{ \App\Models\User::where('role', 'client')->count() }}</h2>
                    <p class="mb-0">Clients</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="row mb-4">
        <div class="col-12 mb-3">
            <h3><i class="bi bi-lightning"></i> Actions rapides</h3>
        </div>
        
        <div class="col-md-3 mb-3">
            <a href="{{ route('chambres.index') }}" class="text-decoration-none">
                <div class="card shadow-sm text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-door-open text-primary" style="font-size: 2.5rem;"></i>
                        <h5 class="mt-3">Gérer les chambres</h5>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3 mb-3">
            <a href="{{ route('admin.reservations.index') }}" class="text-decoration-none">
                <div class="card shadow-sm text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-calendar-check text-success" style="font-size: 2.5rem;"></i>
                        <h5 class="mt-3">Gérer les réservations</h5>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3 mb-3">
            <a href="{{ route('admin.clients.index') }}" class="text-decoration-none">
                <div class="card shadow-sm text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-people text-info" style="font-size: 2.5rem;"></i>
                        <h5 class="mt-3">Gérer les clients</h5>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3 mb-3">
            <a href="{{ route('admin.admins.index') }}" class="text-decoration-none">
                <div class="card shadow-sm text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-shield-check text-danger" style="font-size: 2.5rem;"></i>
                        <h5 class="mt-3">Gérer les admins</h5>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Dernières réservations -->
    <div class="row">
        <div class="col-12 mb-3">
            <h3><i class="bi bi-clock-history"></i> Dernières réservations</h3>
        </div>
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Client</th>
                                <th>Chambre</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th>Prix</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(\App\Models\Reservation::with('client', 'chambre')->latest()->take(5)->get() as $res)
                            <tr>
                                <td>#{{ $res->id }}</td>
                                <td>{{ $res->client->name ?? 'N/A' }}</td>
                                <td>{{ $res->chambre->numero }}</td>
                                <td>{{ \Carbon\Carbon::parse($res->date_arrivee)->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $res->statut === 'confirme' ? 'success' : ($res->statut === 'attente' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($res->statut) }}
                                    </span>
                                </td>
                                <td>{{ number_format($res->prix_total, 0) }} FCFA</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">Aucune réservation</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
@endsection