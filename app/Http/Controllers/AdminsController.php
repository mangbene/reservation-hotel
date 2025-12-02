<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminsController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'admin')->latest()->paginate(15);
        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.admins.create');
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
            'role' => 'admin',
            'telephone' => $request->telephone,
        ]);

        return redirect()->route('admin.admins.index')
            ->with('success', 'Administrateur ajouté avec succès.');
    }

    public function edit($id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);
        return view('admin.admins.edit', compact('admin'));
    }

    public function update(Request $request, $id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);

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

        $admin->update($data);

        return redirect()->route('admin.admins.index')
            ->with('success', 'Administrateur mis à jour avec succès.');
    }

    public function destroy($id)
    {
        // Empêcher la suppression de son propre compte
        if (Auth::id() == $id) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $admin = User::where('role', 'admin')->findOrFail($id);
        $admin->delete();
        
        return redirect()->route('admin.admins.index')
            ->with('success', 'Administrateur supprimé avec succès.');
    }
}
