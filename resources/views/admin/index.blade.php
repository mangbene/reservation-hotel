@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Liste des administrateurs</h1>
    <a href="{{ route('admins.create') }}" class="btn btn-primary mb-3">Ajouter un administrateur</a>

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
            @foreach($admins as $admin)
            <tr>
                <td>{{ $admin->id }}</td>
                <td>{{ $admin->nom }}</td>
                <td>{{ $admin->email }}</td>
                <td>{{ $admin->telephone }}</td>
                <td>
                    <a href="{{ route('admins.edit', $admin->id) }}" class="btn btn-warning btn-sm">Modifier</a>
                    <form action="{{ route('admins.destroy', $admin->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous vraiment supprimer cet administrateur ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
