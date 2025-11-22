@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Modifier la réservation #{{ $reservation->id }}</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('reservations.update', $reservation->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Client</label>
            <select name="client_id" class="form-control">
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ $reservation->client_id == $client->id ? 'selected' : '' }}>{{ $client->nom }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Chambre</label>
            <select name="chambre_id" class="form-control">
                @foreach($chambres as $chambre)
                    <option value="{{ $chambre->id }}" {{ $reservation->chambre_id == $chambre->id ? 'selected' : '' }}>
                        {{ $chambre->numero }} ({{ $chambre->type }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Date d'arrivée</label>
            <input type="date" name="date_arrivee" class="form-control" value="{{ $reservation->date_arrivee }}">
        </div>

        <div class="mb-3">
            <label>Date de départ</label>
            <input type="date" name="date_depart" class="form-control" value="{{ $reservation->date_depart }}">
        </div>

        <div class="mb-3">
            <label>Statut</label>
            <select name="statut" class="form-control">
                <option value="attente" {{ $reservation->statut == 'attente' ? 'selected' : '' }}>Attente</option>
                <option value="confirme" {{ $reservation->statut == 'confirme' ? 'selected' : '' }}>Confirmé</option>
                <option value="annule" {{ $reservation->statut == 'annule' ? 'selected' : '' }}>Annulé</option>
            </select>
        </div>

        <button class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('reservations.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
