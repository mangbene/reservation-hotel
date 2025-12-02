@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1><i class="bi bi-door-open"></i> Gestion des chambres</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Chambres</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.chambres.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Ajouter une chambre
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistiques rapides -->
    <div class="row mb-4 g-3">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body text-center">
                    <h2>{{ $chambres->total() }}</h2>
                    <p class="mb-0">Total chambres</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body text-center">
                    <h2>{{ \App\Models\Chambre::where('statut', 'disponible')->count() }}</h2>
                    <p class="mb-0">Disponibles</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body text-center">
                    <h2>{{ \App\Models\Chambre::where('statut', 'occupee')->count() }}</h2>
                    <p class="mb-0">Occupées</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger">
                <div class="card-body text-center">
                    <h2>{{ \App\Models\Chambre::where('statut', 'maintenance')->count() }}</h2>
                    <p class="mb-0">Maintenance</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des chambres -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Numéro</th>
                            <th>Type</th>
                            <th>Capacité</th>
                            <th>Prix/nuit</th>
                            <th>Statut</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($chambres as $chambre)
                        <tr>
                            <td><strong>#{{ $chambre->id }}</strong></td>
                            <td>
                                <i class="bi bi-door-closed"></i>
                                <strong>{{ $chambre->numero }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ ucfirst($chambre->type) }}</span>
                            </td>
                            <td>
                                <i class="bi bi-people"></i> {{ $chambre->capacite }} pers.
                            </td>
                            <td>
                                <strong>{{ number_format($chambre->prix, 0) }} FCFA</strong>
                            </td>
                            <td>
                                @if($chambre->statut === 'disponible')
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle"></i> Disponible
                                    </span>
                                @elseif($chambre->statut === 'occupee')
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-person-fill"></i> Occupée
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="bi bi-tools"></i> Maintenance
                                    </span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.chambres.edit', $chambre->id) }}" 
                                       class="btn btn-outline-warning"
                                       title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.chambres.destroy', $chambre->id) }}" 
                                          method="POST" 
                                          style="display:inline-block;"
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette chambre ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size: 2rem;"></i><br>
                                Aucune chambre enregistrée
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($chambres->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $chambres->links() }}
    </div>
    @endif
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
@endsection