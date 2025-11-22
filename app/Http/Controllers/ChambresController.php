<?php

namespace App\Http\Controllers;

use App\Models\Chambre; // modèle singulier majuscule
use Illuminate\Http\Request;

class ChambresController extends Controller
{
    // Afficher toutes les chambres
    public function index()
    {
        $chambres = Chambre::all();
        return view('chambres.index', compact('chambres'));
    }

    // Formulaire de création
    public function create()
    {
        return view('chambres.create');
    }

    // Enregistrer une nouvelle chambre
    public function store(Request $request)
    {
        $request->validate([
            'numero' => 'required|unique:chambres',
            'type' => 'required',
            'capacite' => 'required|integer',
            'prix' => 'required|numeric',
            'description' => 'nullable',
            'statut' => 'required'
        ]);

        Chambre::create($request->all());

        return redirect()->route('chambres.index')
                         ->with('success', 'Chambre ajoutée avec succès.');
    }

    // Formulaire d'édition
    public function edit($id)
    {
        $chambre = Chambre::findOrFail($id);
        return view('chambres.edit', compact('chambre'));
    }

    // Mettre à jour une chambre
    public function update(Request $request, $id)
    {
        $chambre = Chambre::findOrFail($id);

        $request->validate([
            'numero' => 'required|unique:chambres,numero,'.$id,
            'type' => 'required',
            'capacite' => 'required|integer',
            'prix' => 'required|numeric',
            'statut' => 'required'
        ]);

        $chambre->update($request->all());

        return redirect()->route('chambres.index')
                         ->with('success', 'Chambre mise à jour avec succès.');
    }

    // Supprimer une chambre
    public function destroy($id)
    {
        Chambre::destroy($id);

        return redirect()->route('chambres.index')
                         ->with('success', 'Chambre supprimée avec succès.');
    }
}
