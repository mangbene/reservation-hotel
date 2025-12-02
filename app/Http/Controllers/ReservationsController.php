<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Chambre;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationsController extends Controller
{
    // ============== MÉTHODES ADMIN ==============
    
    /**
     * Afficher toutes les réservations (Admin)
     */
    public function index()
    {
        $reservations = Reservation::with('client', 'chambre')->latest()->paginate(15);
        return view('admin.reservations.index', compact('reservations'));
    }

    /**
     * Formulaire de création (Admin)
     */
    public function create()
    {
        $clients = User::where('role', 'client')->get();
        $chambres = Chambre::where('statut', 'disponible')->get();
        return view('admin.reservations.create', compact('clients', 'chambres'));
    }

    /**
     * Enregistrer une réservation (Admin)
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:users,id',
            'chambre_id' => 'required|exists:chambres,id',
            'date_arrivee' => 'required|date|after_or_equal:today',
            'date_depart' => 'required|date|after:date_arrivee'
        ]);

        // Vérifier disponibilité
        if ($this->chambreEstOccupee($request->chambre_id, $request->date_arrivee, $request->date_depart)) {
            return back()->withErrors(['chambre_id' => 'La chambre est déjà réservée pour ces dates.'])->withInput();
        }

        // Calcul du prix
        $chambre = Chambre::findOrFail($request->chambre_id);
        $nuits = \Carbon\Carbon::parse($request->date_depart)->diffInDays(\Carbon\Carbon::parse($request->date_arrivee));
        $prix_total = $chambre->prix * $nuits;

        Reservation::create([
            'client_id' => $request->client_id,
            'chambre_id' => $request->chambre_id,
            'date_arrivee' => $request->date_arrivee,
            'date_depart' => $request->date_depart,
            'prix_total' => $prix_total,
            'statut' => 'attente'
        ]);

        return redirect()->route('admin.reservations.index')
            ->with('success', 'Réservation créée avec succès.');
    }

    /**
     * Formulaire d'édition (Admin)
     */
    public function edit($id)
    {
        $reservation = Reservation::findOrFail($id);
        $clients = User::where('role', 'client')->get();
        $chambres = Chambre::all();
        return view('admin.reservations.edit', compact('reservation', 'clients', 'chambres'));
    }

    /**
     * Mettre à jour une réservation (Admin)
     */
    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        $request->validate([
            'client_id' => 'required|exists:users,id',
            'chambre_id' => 'required|exists:chambres,id',
            'date_arrivee' => 'required|date',
            'date_depart' => 'required|date|after:date_arrivee',
            'statut' => 'required|in:attente,confirme,annule'
        ]);

        // Vérifier disponibilité (sauf pour cette réservation)
        if ($this->chambreEstOccupee($request->chambre_id, $request->date_arrivee, $request->date_depart, $id)) {
            return back()->withErrors(['chambre_id' => 'La chambre est déjà réservée pour ces dates.'])->withInput();
        }

        $chambre = Chambre::findOrFail($request->chambre_id);
        $nuits = \Carbon\Carbon::parse($request->date_depart)->diffInDays(\Carbon\Carbon::parse($request->date_arrivee));
        $prix_total = $chambre->prix * $nuits;

        $reservation->update([
            'client_id' => $request->client_id,
            'chambre_id' => $request->chambre_id,
            'date_arrivee' => $request->date_arrivee,
            'date_depart' => $request->date_depart,
            'prix_total' => $prix_total,
            'statut' => $request->statut
        ]);

        return redirect()->route('admin.reservations.index')
            ->with('success', 'Réservation mise à jour avec succès.');
    }

    /**
     * Supprimer une réservation (Admin)
     */
    public function destroy($id)
    {
        Reservation::destroy($id);
        return redirect()->route('admin.reservations.index')
            ->with('success', 'Réservation supprimée avec succès.');
    }

    // ============== MÉTHODES CLIENT ==============
    
    /**
     * Afficher toutes les réservations du client connecté
     */
    public function mesReservations()
    {
        $reservations = Reservation::with('chambre')
            ->where('client_id', Auth::id())
            ->latest()
            ->paginate(10);
        
        return view('client.reservations', compact('reservations'));
    }

    /**
     * Formulaire de réservation pour client
     */
    public function createForClient()
    {
        $chambres = Chambre::where('statut', 'disponible')->get();
        return view('client.reserver', compact('chambres'));
    }

    /**
     * Enregistrer une réservation client
     */
    public function storeForClient(Request $request)
    {
        $request->validate([
            'chambre_id' => 'required|exists:chambres,id',
            'date_arrivee' => 'required|date|after_or_equal:today',
            'date_depart' => 'required|date|after:date_arrivee'
        ]);

        // Vérifier disponibilité
        if ($this->chambreEstOccupee($request->chambre_id, $request->date_arrivee, $request->date_depart)) {
            return back()->withErrors(['chambre_id' => 'La chambre est déjà réservée pour ces dates.'])->withInput();
        }

        // Calcul du prix
        $chambre = Chambre::findOrFail($request->chambre_id);
        $nuits = \Carbon\Carbon::parse($request->date_depart)->diffInDays(\Carbon\Carbon::parse($request->date_arrivee));
        $prix_total = $chambre->prix * $nuits;

        Reservation::create([
            'client_id' => Auth::id(),
            'chambre_id' => $request->chambre_id,
            'date_arrivee' => $request->date_arrivee,
            'date_depart' => $request->date_depart,
            'prix_total' => $prix_total,
            'statut' => 'attente'
        ]);

        return redirect()->route('client.reservations')
            ->with('success', 'Votre réservation a été enregistrée. Elle sera confirmée sous peu.');
    }

    /**
     * Annuler une réservation client
     */
    public function annuler($id)
    {
        $reservation = Reservation::where('id', $id)
            ->where('client_id', Auth::id())
            ->firstOrFail();
        
        // On ne peut annuler que les réservations en attente ou confirmées
        if (in_array($reservation->statut, ['attente', 'confirme'])) {
            $reservation->update(['statut' => 'annule']);
            return back()->with('success', 'Réservation annulée avec succès.');
        }
        
        return back()->with('error', 'Cette réservation ne peut pas être annulée.');
    }

    // ============== MÉTHODES PRIVÉES ==============
    
    /**
     * Vérifier si une chambre est occupée pour les dates données
     */
    private function chambreEstOccupee($chambre_id, $date_arrivee, $date_depart, $except_id = null)
    {
        $query = Reservation::where('chambre_id', $chambre_id)
            ->where('statut', '!=', 'annule')
            ->where(function($q) use ($date_arrivee, $date_depart) {
                $q->whereBetween('date_arrivee', [$date_arrivee, $date_depart])
                  ->orWhereBetween('date_depart', [$date_arrivee, $date_depart])
                  ->orWhere(function($q2) use ($date_arrivee, $date_depart) {
                      $q2->where('date_arrivee', '<=', $date_arrivee)
                         ->where('date_depart', '>=', $date_depart);
                  });
            });

        if ($except_id) {
            $query->where('id', '!=', $except_id);
        }

        return $query->exists();
    }
}