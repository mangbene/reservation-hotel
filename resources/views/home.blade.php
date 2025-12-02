@extends('layouts.app')

@section('content')
<style>
    .hero {
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
                    url('https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1350&q=80') no-repeat center center;
        background-size: cover;
        height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-align: center;
        position: relative;
    }

    .hero-content {
        position: relative;
        z-index: 1;
    }

    .hero h1 {
        font-size: 3.5rem;
        font-weight: bold;
        margin-bottom: 20px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    }

    .hero p {
        font-size: 1.3rem;
        margin-bottom: 30px;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
    }

    .hero .btn {
        font-size: 1.2rem;
        padding: 15px 40px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.3);
    }

    .features {
        padding: 80px 0;
    }

    .feature-card {
        text-align: center;
        padding: 40px 30px;
        border-radius: 15px;
        background-color: #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
    }

    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .feature-card i {
        font-size: 3.5rem;
        margin-bottom: 20px;
        color: #0d6efd;
    }

    .feature-card h3 {
        font-size: 1.5rem;
        margin-bottom: 15px;
        font-weight: bold;
    }

    .feature-card p {
        font-size: 1rem;
        color: #666;
    }

    .cta-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 80px 0;
        text-align: center;
    }

    .cta-section h2 {
        font-size: 2.5rem;
        margin-bottom: 20px;
    }

    .cta-section p {
        font-size: 1.2rem;
        margin-bottom: 30px;
    }
</style>

<!-- Section Hero -->
<div class="hero">
    <div class="hero-content">
        <h1>Bienvenue à l'Hôtel Azur</h1>
        <p>Votre séjour de rêve commence ici</p>
        
        @auth
            @if(Auth::user()->isClient())
                <a href="{{ route('client.reserver') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-calendar-plus"></i> Réserver maintenant
                </a>
            @else
                <a href="{{ route('admin.dashboard') }}" class="btn btn-warning btn-lg">
                    <i class="bi bi-speedometer2"></i> Accéder au dashboard
                </a>
            @endif
        @else
            <div class="d-flex gap-3 justify-content-center">
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-box-arrow-in-right"></i> Reserver Maintenant
                </a>
                
            </div>
        @endauth
    </div>
</div>

<!-- Section Services -->
<div class="features">
    <div class="container">
        <h2 class="text-center mb-5">Nos Services Premium</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card">
                    <i class="bi bi-building"></i>
                    <h3>Chambres de Luxe</h3>
                    <p>Des chambres modernes et confortables équipées de tout le nécessaire pour un séjour inoubliable.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <i class="bi bi-calendar-check"></i>
                    <h3>Réservation Simple</h3>
                    <p>Système de réservation en ligne facile et sécurisé. Réservez en quelques clics !</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <i class="bi bi-headset"></i>
                    <h3>Service 24/24</h3>
                    <p>Notre équipe est disponible 24h/24 pour répondre à tous vos besoins et questions.</p>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-4">
            <div class="col-md-4">
                <div class="feature-card">
                    <i class="bi bi-wifi"></i>
                    <h3>WiFi Gratuit</h3>
                    <p>Connexion internet haut débit gratuite dans toutes les chambres et espaces communs.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <i class="bi bi-shield-check"></i>
                    <h3>Sécurité</h3>
                    <p>Système de sécurité moderne avec surveillance 24h/24 pour votre tranquillité d'esprit.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <i class="bi bi-cup-hot"></i>
                    <h3>Restaurant</h3>
                    <p>Restaurant gastronomique proposant une cuisine locale et internationale de qualité.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section CTA -->
<div class="cta-section">
    <div class="container">
        <h2>Prêt à vivre une expérience unique ?</h2>
        <p>Réservez dès maintenant et profitez de nos offres exceptionnelles</p>
        
        @auth
            @if(Auth::user()->isClient())
                <a href="{{ route('client.reserver') }}" class="btn btn-light btn-lg">
                    <i class="bi bi-calendar-plus"></i> Faire une réservation
                </a>
            @else
                <a href="{{ route('admin.reservations.index') }}" class="btn btn-light btn-lg">
                    <i class="bi bi-list-check"></i> Gérer les réservations
                </a>
            @endif
        @else
            <a href="{{ route('register') }}" class="btn btn-light btn-lg">
                <i class="bi bi-person-plus"></i> Créer un compte
            </a>
        @endauth
    </div>
</div>

<!-- Section Info -->
<div class="container py-5">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h2 class="mb-4">Pourquoi choisir l'Hôtel Azur ?</h2>
            <ul class="list-unstyled">
                <li class="mb-3">
                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                    <strong>Emplacement idéal</strong> - Au cœur de la ville
                </li>
                <li class="mb-3">
                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                    <strong>Tarifs compétitifs</strong> - Meilleur rapport qualité/prix
                </li>
                <li class="mb-3">
                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                    <strong>Personnel qualifié</strong> - Service professionnel et chaleureux
                </li>
                <li class="mb-3">
                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                    <strong>Chambres modernes</strong> - Équipements de dernière génération
                </li>
            </ul>
        </div>
        <div class="col-md-6">
            <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=800&q=80" 
                 alt="Hôtel" 
                 class="img-fluid rounded shadow-lg">
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
@endsection