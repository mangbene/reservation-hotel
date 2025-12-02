<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClientsController extends Controller
{
    public function index()
    {
        $clients = User::where('role', 'client')->latest()->paginate(15);
        return view('admin.clients.index', compact('clients'));
    }

    public function create()
    {
        return view('admin.clients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'telephone' => 'nullable|string|max:20'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'client',
            'telephone' => $request->telephone,
        ]);

        return redirect()->route('admin.clients.index')
            ->with('success', 'Client ajouté avec succès.');
    }

    public function edit($id)
    {
        $client = User::where('role', 'client')->findOrFail($id);
        return view('admin.clients.edit', compact('client'));
    }

    public function update(Request $request, $id)
    {
        $client = User::where('role', 'client')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'telephone' => 'nullable|string|max:20'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'telephone' => $request->telephone,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $client->update($data);

        return redirect()->route('admin.clients.index')
            ->with('success', 'Client mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $client = User::where('role', 'client')->findOrFail($id);
        
        // Vérifier si le client a des réservations actives
        if ($client->reservations()->whereIn('statut', ['confirme', 'attente'])->exists()) {
            return back()->with('error', 'Impossible de supprimer ce client car il a des réservations actives.');
        }

        $client->delete();
        
        return redirect()->route('admin.clients.index')
            ->with('success', 'Client supprimé avec succès.');
    }
}