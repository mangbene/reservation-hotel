@extends('layouts.app')

@section('content')
<style>
    /* Ton style actuel pour la page d'accueil */
    .hero {
        background: url('https://images.unsplash.com/photo-1559589689-5777d708f7b0?auto=format&fit=crop&w=1350&q=80') no-repeat center center;
        background-size: cover;
        height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-align: center;
        position: relative;
    }

    .hero::after {
        content: '';
        position: absolute;
        top:0; left:0; right:0; bottom:0;
        background-color: rgba(0,0,0,0.5);
    }

    .hero-content {
        position: relative;
        z-index: 1;
    }

    .hero h1 {
        font-size: 3rem;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .hero p {
        font-size: 1.2rem;
        margin-bottom: 30px;
    }

    .hero a.btn {
        font-size: 1.2rem;
        padding: 12px 30px;
    }

    .features {
        display: flex;
        justify-content: space-around;
        flex-wrap: wrap;
        margin: 60px 0;
        text-align: center;
    }

    .feature {
        flex: 1 1 250px;
        margin: 20px;
        padding: 30px;
        border-radius: 15px;
        background-color: #f4f6f8ff;
        transition: transform 0.3s ease;
    }

    .feature:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }

    .feature i {
        font-size: 50px;
        margin-bottom: 15px;
        color: #0d6efd;
    }

    .feature h3 {
        font-size: 1.5rem;
        margin-bottom: 15px;
    }

    .feature p {
        font-size: 1rem;
        color: #555;
    }

    /* Style formulaires auth */
    .auth-forms {
        margin: 60px 0;
    }

    .auth-forms .card {
        margin-bottom: 30px;
    }
</style>

<div class="hero">
    <div class="hero-content">
        <h1>Bienvenue à l’Hôtel Azure</h1>
        <p>Réservez votre chambre en ligne en toute simplicité</p>
        <a href="{{ route('reservations.create') }}" class="btn btn-primary">Réserver maintenant</a>
    </div>
</div>

<div class="container">
    <h2 class="text-center mb-5">Nos Services</h2>
    <div class="features">
        <div class="feature">
            <i class="bi bi-hotel"></i>
            <h3>Chambres Confortables</h3>
            <p>Profitez de chambres équipées avec tout le confort moderne pour un séjour agréable.</p>
        </div>
        <div class="feature">
            <i class="bi bi-calendar-check"></i>
            <h3>Réservation Facile</h3>
            <p>Réservez votre chambre en quelques clics et gérez vos réservations facilement.</p>
        </div>
        <div class="feature">
            <i class="bi bi-people"></i>
            <h3>Service Client</h3>
            <p>Notre équipe est disponible 24h/24 pour répondre à toutes vos demandes.</p>
        </div>
    </div>
</div>

<!-- Formulaires Inscription et Connexion -->
<div class="container auth-forms">
    <div class="row">
        <!-- Inscription -->
        <div class="col-md-6">
            <div class="card p-4">
                <h3 class="mb-3">Inscription</h3>
                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>Nom</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                    </div>
                    <div class="mb-3">
                        <label>Mot de passe</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Confirmer le mot de passe</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Rôle</label>
                        <select name="role" class="form-control">
                            <option value="client">Client</option>
                            <option value="admin">Administrateur</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">S'inscrire</button>
                </form>
            </div>
        </div>

        <!-- Connexion -->
        <div class="col-md-6">
            <div class="card p-4">
                <h3 class="mb-3">Connexion</h3>
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                    </div>
                    <div class="mb-3">
                        <label>Mot de passe</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-success">Se connecter</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
