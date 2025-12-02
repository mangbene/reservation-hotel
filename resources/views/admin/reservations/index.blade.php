@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1><i class="bi bi-calendar-check"></i> Gestion des réservations</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Réservations</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.reservations.create') }}" class="btn btn-primary">
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

    <!-- Statistiques -->
    <div class="row mb-4 g-3">
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body text-center">
                    <h2>{{ $reservations->total() }}</h2>
                    <p class="mb-0">Total réservations</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body text-center">
                    <h2>{{ \App\Models\Reservation::where('statut', 'attente')->count() }}</h2>
                    <p class="mb-0">En attente</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body text-center">
                    <h2>{{ \App\Models\Reservation::where('statut', 'confirme')->count() }}</h2>
                    <p class="mb-0">Confirmées</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger">
                <div class="card-body text-center">
                    <h2>{{ \App\Models\Reservation::where('statut', 'annule')->count() }}</h2>
                    <p class="mb-0">Annulées</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Chambre</th>
                            <th>Arrivée</th>
                            <th>Départ</th>
                            <th>Prix</th>
                            <th>Statut</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservations as $res)
                        <tr>
                            <td><strong>#{{ $res->id }}</strong></td>
                            <td>
                                <i class="bi bi-person-circle"></i>
                                {{ $res->client->name ?? 'N/A' }}
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $res->chambre->numero }}</span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($res->date_arrivee)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($res->date_depart)->format('d/m/Y') }}</td>
                            <td><strong>{{ number_format($res->prix_total, 0) }} FCFA</strong></td>
                            <td>
                                @if($res->statut === 'confirme')
                                    <span class="badge bg-success">Confirmée</span>
                                @elseif($res->statut === 'attente')
                                    <span class="badge bg-warning text-dark">En attente</span>
                                @else
                                    <span class="badge bg-danger">Annulée</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.reservations.edit', $res->id) }}" 
                                       class="btn btn-outline-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.reservations.destroy', $res->id) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Supprimer cette réservation ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox" style="font-size: 2rem;"></i><br>
                                Aucune réservation
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($reservations->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $reservations->links() }}
    </div>
    @endif
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
@endsection