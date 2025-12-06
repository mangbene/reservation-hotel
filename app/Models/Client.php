<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clients';

    // ✅ CORRECTION : Tous les champs autorisés
    protected $fillable = [
        'nom',
        'email',
        'mot_de_passe',
        'role',
        'telephone'
    ];

    // Cacher le mot de passe dans les réponses JSON
    protected $hidden = [
        'mot_de_passe'
    ];

    // Relation avec les réservations
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    // Hash du mot de passe avant enregistrement
    public function setMotDePasseAttribute($value)
    {
        // Seulement hasher si la valeur n'est pas vide
        if (!empty($value)) {
            $this->attributes['mot_de_passe'] = bcrypt($value);
        }
    }
}