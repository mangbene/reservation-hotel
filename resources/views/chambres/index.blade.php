@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-door-open"></i> Liste des chambres</h1>
        <a href="{{ route('chambres.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Ajouter une chambre
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow">
        <div class="card-body">
            @if($chambres->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Numéro</th>
                                <th>Type</th>
                                <th>Capacité</th>
                                <th>Prix / nuit</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($chambres as $chambre)
                            <tr>
                                <td>{{ $chambre->id }}</td>
                                <td><strong>{{ $chambre->numero }}</strong></td>
                                <td>
                                    <span class="badge bg-info">{{ ucfirst($chambre->type) }}</span>
                                </td>
                                <td>
                                    <i class="bi bi-people"></i> {{ $chambre->capacite }} pers.
                                </td>
                                <td><strong>{{ number_format($chambre->prix, 2) }} €</strong></td>
                                <td>
                                    @if($chambre->statut == 'disponible')
                                        <span class="badge bg-success">Disponible</span>
                                    @elseif($chambre->statut == 'occupee')
                                        <span class="badge bg-danger">Occupée</span>
                                    @else
                                        <span class="badge bg-warning">Maintenance</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('chambres.edit', $chambre->id) }}" 
                                           class="btn btn-sm btn-warning" 
                                           title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('chambres.destroy', $chambre->id) }}" 
                                              method="POST" 
                                              style="display:inline-block;"
                                              onsubmit="return confirm('Voulez-vous vraiment supprimer cette chambre ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                    <p class="text-muted mt-3">Aucune chambre enregistrée.</p>
                    <a href="{{ route('chambres.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Ajouter la première chambre
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection