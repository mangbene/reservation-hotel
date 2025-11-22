<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;

class AdminsController extends Controller
{
    public function index()
    {
        $admins = Admin::all();
        return view('admins.index', compact('admins'));
    }

    public function create()
    {
        return view('admins.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'mot_de_passe' => 'required|string|min:6',
            'telephone' => 'nullable|string|max:20'
        ]);

        Admin::create($request->all());

        return redirect()->route('admins.index')
                         ->with('success', 'Administrateur ajouté avec succès.');
    }

    public function edit($id)
    {
        $admin = Admin::findOrFail($id);
        return view('admins.edit', compact('admin'));
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,'.$id,
            'mot_de_passe' => 'nullable|string|min:6',
            'telephone' => 'nullable|string|max:20'
        ]);

        $data = $request->all();
        if(empty($data['mot_de_passe'])){
            unset($data['mot_de_passe']);
        }

        $admin->update($data);

        return redirect()->route('admins.index')
                         ->with('success', 'Administrateur mis à jour avec succès.');
    }

    public function destroy($id)
    {
        Admin::destroy($id);
        return redirect()->route('admins.index')
                         ->with('success', 'Administrateur supprimé avec succès.');
    }
}
