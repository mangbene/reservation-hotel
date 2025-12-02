<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ========== AFFICHER LES PAGES ==========
    
    /**
     * Affiche la page d'accueil
     */
    public function showHome() 
    {
        return view('home');
    }

    /**
     * Affiche le formulaire de connexion
     */
    public function showLogin() 
    {
        return view('auth.login');
    }

    /**
     * Affiche le formulaire d'inscription
     */
    public function showRegister() 
    {
        return view('auth.register');
    }

    // ========== TRAITER LES FORMULAIRES ==========
    
    /**
     * Traiter l'inscription
     */
    public function register(Request $request) 
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,client',
            'telephone' => 'nullable|string|max:20'
        ], [
            'name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'role.required' => 'Veuillez choisir un type de compte.'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'telephone' => $request->telephone,
        ]);

        // Connecter automatiquement l'utilisateur
        Auth::login($user);

        // Rediriger selon le rôle
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('success', 'Bienvenue ' . $user->name . ' ! Votre compte administrateur a été créé.');
        }

        return redirect()->route('client.dashboard')
            ->with('success', 'Bienvenue ' . $user->name . ' ! Votre compte a été créé avec succès.');
    }

    /**
     * Traiter la connexion
     */
    public function login(Request $request) 
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'Veuillez entrer un email valide.',
            'password.required' => 'Le mot de passe est obligatoire.'
        ]);

        // Tentative de connexion
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Rediriger selon le rôle
            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', 'Bienvenue ' . $user->name . ' !');
            }
            
            return redirect()->intended(route('client.dashboard'))
                ->with('success', 'Bienvenue ' . $user->name . ' !');
        }

        // Si échec de connexion
        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->withInput($request->only('email'));
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request) 
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('home')
            ->with('success', 'Vous avez été déconnecté avec succès.');
    }
}