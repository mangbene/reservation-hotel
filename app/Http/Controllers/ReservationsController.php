<?php
namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Chambre;
use App\Models\Client;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservationsController extends Controller
{
    /**
     * Afficher la liste des réservations
     */
    public function index()
    {
        $reservations = Reservation::with('client', 'chambre')
            ->orderBy('date_arrivee', 'desc')
            ->get();
        return view('reservations.index', compact('reservations'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $clients = Client::orderBy('nom', 'asc')->get();
        $chambres = Chambre::where('statut', 'disponible')->orderBy('numero', 'asc')->get();
        return view('reservations.create', compact('clients', 'chambres'));
    }

    /**
     * Enregistrer une nouvelle réservation
     */
    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'chambre_id' => 'required|exists:chambres,id',
            'date_arrivee' => 'required|date|after_or_equal:today',
            'date_depart' => 'required|date|after:date_arrivee'
        ], [
            'client_id.required' => 'Veuillez sélectionner un client.',
            'client_id.exists' => 'Le client sélectionné n\'existe pas.',
            'chambre_id.required' => 'Veuillez sélectionner une chambre.',
            'chambre_id.exists' => 'La chambre sélectionnée n\'existe pas.',
            'date_arrivee.required' => 'La date d\'arrivée est requise.',
            'date_arrivee.after_or_equal' => 'La date d\'arrivée doit être aujourd\'hui ou plus tard.',
            'date_depart.required' => 'La date de départ est requise.',
            'date_depart.after' => 'La date de départ doit être après la date d\'arrivée.'
        ]);

        // Vérifier la disponibilité de la chambre
        $existing = Reservation::where('chambre_id', $request->chambre_id)
            ->where('statut', '!=', 'annule')
            ->where(function($query) use ($request) {
                $query->whereBetween('date_arrivee', [$request->date_arrivee, $request->date_depart])
                      ->orWhereBetween('date_depart', [$request->date_arrivee, $request->date_depart])
                      ->orWhere(function($q) use ($request) {
                          $q->where('date_arrivee', '<=', $request->date_arrivee)
                            ->where('date_depart', '>=', $request->date_depart);
                      });
            })
            ->exists();

        if ($existing) {
            return back()
                ->withErrors(['chambre_id' => 'La chambre est déjà réservée pour ces dates.'])
                ->withInput();
        }

        // Récupérer la chambre pour le calcul du prix
        $chambre = Chambre::findOrFail($request->chambre_id);
        
        // Calculer le nombre de nuits
        $dateArrivee = Carbon::parse($request->date_arrivee);
        $dateDepart = Carbon::parse($request->date_depart);
        $nombreNuits = $dateArrivee->diffInDays($dateDepart);
        
        // Calculer le prix total
        $prixTotal = $chambre->prix * $nombreNuits;

        // Créer la réservation
        Reservation::create([
            'client_id' => $validated['client_id'],
            'chambre_id' => $validated['chambre_id'],
            'date_arrivee' => $validated['date_arrivee'],
            'date_depart' => $validated['date_depart'],
            'prix_total' => $prixTotal,
            'statut' => 'attente'
        ]);

        return redirect()->route('reservations.index')
            ->with('success', '✅ Réservation créée avec succès ! Prix total : ' . number_format($prixTotal, 2) . ' €');
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit($id)
    {
        $reservation = Reservation::with('client', 'chambre')->findOrFail($id);
        $clients = Client::orderBy('nom', 'asc')->get();
        $chambres = Chambre::orderBy('numero', 'asc')->get();
        return view('reservations.edit', compact('reservation', 'clients', 'chambres'));
    }

    /**
     * Mettre à jour une réservation
     */
    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        // Validation des données
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'chambre_id' => 'required|exists:chambres,id',
            'date_arrivee' => 'required|date',
            'date_depart' => 'required|date|after:date_arrivee',
            'statut' => 'required|in:attente,confirme,annule'
        ], [
            'client_id.required' => 'Veuillez sélectionner un client.',
            'client_id.exists' => 'Le client sélectionné n\'existe pas.',
            'chambre_id.required' => 'Veuillez sélectionner une chambre.',
            'chambre_id.exists' => 'La chambre sélectionnée n\'existe pas.',
            'date_arrivee.required' => 'La date d\'arrivée est requise.',
            'date_depart.required' => 'La date de départ est requise.',
            'date_depart.after' => 'La date de départ doit être après la date d\'arrivée.',
            'statut.required' => 'Le statut est requis.',
            'statut.in' => 'Le statut doit être : attente, confirmé ou annulé.'
        ]);

        // Vérifier la disponibilité (sauf pour cette réservation)
        $existing = Reservation::where('chambre_id', $request->chambre_id)
            ->where('id', '!=', $id)
            ->where('statut', '!=', 'annule')
            ->where(function($query) use ($request) {
                $query->whereBetween('date_arrivee', [$request->date_arrivee, $request->date_depart])
                      ->orWhereBetween('date_depart', [$request->date_arrivee, $request->date_depart])
                      ->orWhere(function($q) use ($request) {
                          $q->where('date_arrivee', '<=', $request->date_arrivee)
                            ->where('date_depart', '>=', $request->date_depart);
                      });
            })
            ->exists();

        if ($existing) {
            return back()
                ->withErrors(['chambre_id' => 'La chambre est déjà réservée pour ces dates.'])
                ->withInput();
        }

        // Récupérer la chambre pour le calcul du prix
        $chambre = Chambre::findOrFail($request->chambre_id);
        
        // Calculer le nombre de nuits
        $dateArrivee = Carbon::parse($request->date_arrivee);
        $dateDepart = Carbon::parse($request->date_depart);
        $nombreNuits = $dateArrivee->diffInDays($dateDepart);
        
        // Calculer le prix total
        $prixTotal = $chambre->prix * $nombreNuits;

        // Mettre à jour la réservation
        $reservation->update([
            'client_id' => $validated['client_id'],
            'chambre_id' => $validated['chambre_id'],
            'date_arrivee' => $validated['date_arrivee'],
            'date_depart' => $validated['date_depart'],
            'prix_total' => $prixTotal,
            'statut' => $validated['statut']
        ]);

        return redirect()->route('reservations.index')
            ->with('success', '✅ Réservation mise à jour avec succès ! Prix total : ' . number_format($prixTotal, 2) . ' €');
    }

    /**
     * Supprimer une réservation
     */
    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();

        return redirect()->route('reservations.index')
            ->with('success', '✅ Réservation supprimée avec succès !');
    }

    /**
     * Afficher le formulaire de réservation pour un client spécifique
     */
    public function createForClient($clientId = null)
    {
        // Si un client_id est fourni, le récupérer
        $client = $clientId ? Client::findOrFail($clientId) : null;
        
        // Récupérer toutes les chambres disponibles
        $chambres = Chambre::where('statut', 'disponible')->orderBy('numero', 'asc')->get();
        
        return view('reservations.create-client', compact('client', 'chambres'));
    }

    /**
     * Enregistrer une réservation pour un client spécifique (utilisé par les clients)
     */
    public function storeForClient(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'chambre_id' => 'required|exists:chambres,id',
            'date_arrivee' => 'required|date|after_or_equal:today',
            'date_depart' => 'required|date|after:date_arrivee'
        ], [
            'client_id.required' => 'Le client est requis.',
            'client_id.exists' => 'Le client sélectionné n\'existe pas.',
            'chambre_id.required' => 'Veuillez sélectionner une chambre.',
            'chambre_id.exists' => 'La chambre sélectionnée n\'existe pas.',
            'date_arrivee.required' => 'La date d\'arrivée est requise.',
            'date_arrivee.after_or_equal' => 'La date d\'arrivée doit être aujourd\'hui ou plus tard.',
            'date_depart.required' => 'La date de départ est requise.',
            'date_depart.after' => 'La date de départ doit être après la date d\'arrivée.'
        ]);

        // Vérifier la disponibilité de la chambre
        $existing = Reservation::where('chambre_id', $request->chambre_id)
            ->where('statut', '!=', 'annule')
            ->where(function($query) use ($request) {
                $query->whereBetween('date_arrivee', [$request->date_arrivee, $request->date_depart])
                      ->orWhereBetween('date_depart', [$request->date_arrivee, $request->date_depart])
                      ->orWhere(function($q) use ($request) {
                          $q->where('date_arrivee', '<=', $request->date_arrivee)
                            ->where('date_depart', '>=', $request->date_depart);
                      });
            })
            ->exists();

        if ($existing) {
            return back()
                ->withErrors(['chambre_id' => 'La chambre est déjà réservée pour ces dates.'])
                ->withInput();
        }

        // Récupérer la chambre pour le calcul du prix
        $chambre = Chambre::findOrFail($request->chambre_id);
        
        // Calculer le nombre de nuits
        $dateArrivee = Carbon::parse($request->date_arrivee);
        $dateDepart = Carbon::parse($request->date_depart);
        $nombreNuits = $dateArrivee->diffInDays($dateDepart);
        
        // Calculer le prix total
        $prixTotal = $chambre->prix * $nombreNuits;

        // Créer la réservation
        Reservation::create([
            'client_id' => $validated['client_id'],
            'chambre_id' => $validated['chambre_id'],
            'date_arrivee' => $validated['date_arrivee'],
            'date_depart' => $validated['date_depart'],
            'prix_total' => $prixTotal,
            'statut' => 'attente'
        ]);

        return redirect()->route('reservations.index')
            ->with('success', '✅ Réservation créée avec succès ! Prix total : ' . number_format($prixTotal, 2) . ' €');
    }
}