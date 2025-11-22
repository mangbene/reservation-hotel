<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Éditer la chambre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
    <h1>Éditer la chambre {{ $chambre->numero }}</h1>
    <a href="{{ route('chambres.index') }}" class="btn btn-secondary mb-3">Retour à la liste</a>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
        </div>
    @endif

    <form action="{{ route('chambres.update', $chambre->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3"><label>Numéro</label><input type="text" name="numero" class="form-control" value="{{ $chambre->numero }}" required></div>
        <div class="mb-3"><label>Type</label>
            <select name="type" class="form-control" required>
                <option value="simple" {{ $chambre->type == 'simple' ? 'selected' : '' }}>Simple</option>
                <option value="double" {{ $chambre->type == 'double' ? 'selected' : '' }}>Double</option>
                <option value="suite" {{ $chambre->type == 'suite' ? 'selected' : '' }}>Suite</option>
            </select>
        </div>
        <div class="mb-3"><label>Capacité</label><input type="number" name="capacite" class="form-control" value="{{ $chambre->capacite }}" required></div>
        <div class="mb-3"><label>Prix (€)</label><input type="number" step="0.01" name="prix" class="form-control" value="{{ $chambre->prix }}" required></div>
        <div class="mb-3"><label>Description</label><textarea name="description" class="form-control">{{ $chambre->description }}</textarea></div>
        <div class="mb-3"><label>Statut</label>
            <select name="statut" class="form-control" required>
                <option value="disponible" {{ $chambre->statut == 'disponible' ? 'selected' : '' }}>Disponible</option>
                <option value="occupee" {{ $chambre->statut == 'occupee' ? 'selected' : '' }}>Occupée</option>
                <option value="maintenance" {{ $chambre->statut == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Mettre à jour</button>
    </form>
</div>
</body>
</html>
