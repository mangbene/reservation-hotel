@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Liste des réservations</h1>
    <a href="{{ route('reservations.create') }}" class="btn btn-primary mb-3">Nouvelle réservation</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Client</th>
                <th>Chambre</th>
                <th>Date d'arrivée</th>
                <th>Date de départ</th>
                <th>Prix total</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservations as $reservation)
            <tr>
                <td>{{ $reservation->id }}</td>
                <td>{{ $reservation->client->nom }}</td>
                <td>{{ $reservation->chambre->numero }}</td>
                <td>{{ $reservation->date_arrivee }}</td>
                <td>{{ $reservation->date_depart }}</td>
                <td>{{ $reservation->prix_total }}</td>
                <td>{{ $reservation->statut }}</td>
                <td>
                    <a href="{{ route('reservations.edit', $reservation->id) }}" class="btn btn-warning btn-sm">Modifier</a>
                    <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous vraiment supprimer cette réservation ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
