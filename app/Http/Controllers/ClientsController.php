<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
    public function index()
    {
        $clients = Client::all();
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'mot_de_passe' => 'required|string|min:6',
            'telephone' => 'nullable|string|max:20'
        ]);

        Client::create($request->all());

        return redirect()->route('clients.index')
                         ->with('success', 'Client ajouté avec succès.');
    }

    public function edit($id)
    {
        $client = Client::findOrFail($id);
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);

        $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,'.$id,
            'mot_de_passe' => 'nullable|string|min:6',
            'telephone' => 'nullable|string|max:20'
        ]);

        $data = $request->all();
        if(empty($data['mot_de_passe'])){
            unset($data['mot_de_passe']);
        }

        $client->update($data);

        return redirect()->route('clients.index')
                         ->with('success', 'Client mis à jour avec succès.');
    }

    public function destroy($id)
    {
        Client::destroy($id);
        return redirect()->route('clients.index')
                         ->with('success', 'Client supprimé avec succès.');
    }
}
