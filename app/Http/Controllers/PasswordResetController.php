<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;

class PasswordResetController extends Controller
{
    /**
     * Afficher le formulaire de demande de réinitialisation
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Envoyer le lien de réinitialisation par email
     */
    public function sendResetLinkEmail(Request $request)
    {
        // Validation de l'email
        $request->validate([
            'email' => 'required|email'
        ], [
            'email.required' => 'L\'adresse email est requise.',
            'email.email' => 'L\'adresse email doit être valide.'
        ]);

        // Vérifier si l'utilisateur existe
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->withErrors([
                'email' => 'Aucun compte ne correspond à cette adresse email.'
            ])->withInput();
        }

        // Envoyer le lien de réinitialisation
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Vérifier si l'envoi a réussi
        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'Un lien de réinitialisation a été envoyé à votre adresse email. Vérifiez votre boîte de réception.');
        }

        return back()->withErrors([
            'email' => 'Impossible d\'envoyer le lien de réinitialisation. Veuillez réessayer plus tard.'
        ])->withInput();
    }

    /**
     * Afficher le formulaire de réinitialisation du mot de passe
     */
    public function showResetForm(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Réinitialiser le mot de passe
     */
    public function reset(Request $request)
    {
        // Validation des données
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ], [
            'token.required' => 'Le token de réinitialisation est manquant.',
            'email.required' => 'L\'adresse email est requise.',
            'email.email' => 'L\'adresse email doit être valide.',
            'password.required' => 'Le mot de passe est requis.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        // Réinitialiser le mot de passe
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                // Mettre à jour le mot de passe
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                // Déclencher l'événement de réinitialisation
                event(new PasswordReset($user));
            }
        );

        // Vérifier le statut de la réinitialisation
        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('home')->with('status', '✅ Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter.');
        }

        // En cas d'erreur (token expiré, invalide, etc.)
        return back()->withErrors([
            'email' => 'Le lien de réinitialisation est invalide ou a expiré. Veuillez faire une nouvelle demande.'
        ])->withInput();
    }
}