@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Liste des chambres</h1>
    <a href="{{ route('chambres.create') }}" class="btn btn-primary mb-3">Ajouter une chambre</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
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
                <td>{{ $chambre->numero }}</td>
                <td>{{ $chambre->type }}</td>
                <td>{{ $chambre->capacite }}</td>
                <td>{{ $chambre->prix }}</td>
                <td>{{ $chambre->statut }}</td>
                <td>
                    <a href="{{ route('chambres.edit', $chambre->id) }}" class="btn btn-warning btn-sm">Modifier</a>
                    <form action="{{ route('chambres.destroy', $chambre->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous vraiment supprimer cette chambre ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
