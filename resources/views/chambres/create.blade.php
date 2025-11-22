<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une chambre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
    <h1>Ajouter une chambre</h1>
    <a href="{{ route('chambres.index') }}" class="btn btn-secondary mb-3">Retour à la liste</a>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
        </div>
    @endif

    <form action="{{ route('chambres.store') }}" method="POST">
        @csrf
        <div class="mb-3"><label>Numéro</label><input type="text" name="numero" class="form-control" required></div>
        <div class="mb-3"><label>Type</label>
            <select name="type" class="form-control" required>
                <option value="simple">Simple</option>
                <option value="double">Double</option>
                <option value="suite">Suite</option>
            </select>
        </div>
        <div class="mb-3"><label>Capacité</label><input type="number" name="capacite" class="form-control" required></div>
        <div class="mb-3"><label>Prix (€)</label><input type="number" step="0.01" name="prix" class="form-control" required></div>
        <div class="mb-3"><label>Description</label><textarea name="description" class="form-control"></textarea></div>
        <div class="mb-3"><label>Statut</label>
            <select name="statut" class="form-control" required>
                <option value="disponible">Disponible</option>
                <option value="occupee">Occupée</option>
                <option value="maintenance">Maintenance</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Ajouter</button>
    </form>
</div>
</body>
</html>
