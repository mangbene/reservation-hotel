@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-people"></i> Gestion des clients</h1>
        <a href="{{ route('clients.create') }}" class="btn btn-success">
            <i class="bi bi-person-plus"></i> Ajouter un client
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
            @if($clients->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Date d'inscription</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($clients as $client)
                            <tr>
                                <td><strong>{{ $client->id }}</strong></td>
                                <td>
                                    <i class="bi bi-person-circle text-primary"></i> 
                                    <strong>{{ $client->nom }}</strong>
                                </td>
                                <td>
                                    <i class="bi bi-envelope"></i> 
                                    <a href="mailto:{{ $client->email }}">{{ $client->email }}</a>
                                </td>
                                <td>
                                    @if($client->telephone)
                                        <i class="bi bi-telephone"></i> {{ $client->telephone }}
                                    @else
                                        <span class="text-muted">Non renseigné</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar"></i>
                                        {{ $client->created_at->format('d/m/Y') }}
                                    </small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('clients.edit', $client->id) }}" 
                                           class="btn btn-sm btn-warning"
                                           title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('clients.destroy', $client->id) }}" 
                                              method="POST" 
                                              style="display:inline-block;"
                                              onsubmit="return confirm('⚠️ Voulez-vous vraiment supprimer ce client ?\n\nCette action est irréversible et supprimera également toutes ses réservations.')">
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

                <!-- Statistiques -->
                <div class="mt-3">
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle"></i>
                        <strong>Total :</strong> {{ $clients->count() }} client(s) enregistré(s)
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-people" style="font-size: 4rem; color: #ccc;"></i>
                    <h4 class="text-muted mt-3">Aucun client enregistré</h4>
                    <p class="text-muted">Commencez par ajouter votre premier client !</p>
                    <a href="{{ route('clients.create') }}" class="btn btn-success btn-lg">
                        <i class="bi bi-person-plus"></i> Ajouter le premier client
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection