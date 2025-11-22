@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Liste des clients</h1>
    <a href="{{ route('clients.create') }}" class="btn btn-primary mb-3">Ajouter un client</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clients as $client)
            <tr>
                <td>{{ $client->id }}</td>
                <td>{{ $client->nom }}</td>
                <td>{{ $client->email }}</td>
                <td>{{ $client->telephone }}</td>
                <td>
                    <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-warning btn-sm">Modifier</a>
                    <form action="{{ route('clients.destroy', $client->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous vraiment supprimer ce client ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
