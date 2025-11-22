<?php
namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Chambre;
use App\Models\Client;
use Illuminate\Http\Request;

class ReservationsController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with('client', 'chambre')->get();
        return view('reservations.index', compact('reservations'));
    }

    public function create()
    {
        $clients = Client::all();
        $chambres = Chambre::where('statut', 'disponible')->get();
        return view('reservations.create', compact('clients', 'chambres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'chambre_id' => 'required|exists:chambres,id',
            'date_arrivee' => 'required|date|after_or_equal:today',
            'date_depart' => 'required|date|after:date_arrivee'
        ]);

        // Vérifier disponibilité
        $existing = Reservation::where('chambre_id', $request->chambre_id)
            ->where('statut', '!=', 'annule')
            ->where(function($q) use ($request){
                $q->whereBetween('date_arrivee', [$request->date_arrivee, $request->date_depart])
                  ->orWhereBetween('date_depart', [$request->date_arrivee, $request->date_depart]);
            })->count();

        if($existing > 0){
            return back()->withErrors(['chambre_id' => 'La chambre est déjà réservée pour ces dates.'])->withInput();
        }

        // Calcul du prix total
        $chambre = Chambre::findOrFail($request->chambre_id);
        $nuit = (strtotime($request->date_depart) - strtotime($request->date_arrivee)) / 86400;
        $prix_total = $chambre->prix * $nuit;

        Reservation::create([
            'client_id' => $request->client_id,
            'chambre_id' => $request->chambre_id,
            'date_arrivee' => $request->date_arrivee,
            'date_depart' => $request->date_depart,
            'prix_total' => $prix_total,
            'statut' => 'attente'
        ]);

        return redirect()->route('reservations.index')->with('success', 'Réservation créée avec succès.');
    }

    public function edit($id)
    {
        $reservation = Reservation::findOrFail($id);
        $clients = Client::all();
        $chambres = Chambre::all();
        return view('reservations.edit', compact('reservation', 'clients', 'chambres'));
    }

    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'chambre_id' => 'required|exists:chambres,id',
            'date_arrivee' => 'required|date|after_or_equal:today',
            'date_depart' => 'required|date|after:date_arrivee',
            'statut' => 'required|in:attente,confirme,annule'
        ]);

        // Vérifier disponibilité (sauf pour cette réservation)
        $existing = Reservation::where('chambre_id', $request->chambre_id)
            ->where('id', '!=', $id)
            ->where('statut', '!=', 'annule')
            ->where(function($q) use ($request){
                $q->whereBetween('date_arrivee', [$request->date_arrivee, $request->date_depart])
                  ->orWhereBetween('date_depart', [$request->date_arrivee, $request->date_depart]);
            })->count();

        if($existing > 0){
            return back()->withErrors(['chambre_id' => 'La chambre est déjà réservée pour ces dates.'])->withInput();
        }

        $chambre = Chambre::findOrFail($request->chambre_id);
        $nuit = (strtotime($request->date_depart) - strtotime($request->date_arrivee)) / 86400;
        $prix_total = $chambre->prix * $nuit;

        $reservation->update([
            'client_id' => $request->client_id,
            'chambre_id' => $request->chambre_id,
            'date_arrivee' => $request->date_arrivee,
            'date_depart' => $request->date_depart,
            'prix_total' => $prix_total,
            'statut' => $request->statut
        ]);

        return redirect()->route('reservations.index')->with('success', 'Réservation mise à jour avec succès.');
    }

    public function destroy($id)
    {
        Reservation::destroy($id);
        return redirect()->route('reservations.index')->with('success', 'Réservation supprimée avec succès.');
    }
}
