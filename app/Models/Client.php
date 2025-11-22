<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clients';

    protected $fillable = [
        'nom',
        'email',
        'mot_de_passe',
        'role',
        'telephone'
    ];

    // Relation avec les réservations
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    // Hash du mot de passe avant enregistrement
    public function setMotDePasseAttribute($value)
    {
        $this->attributes['mot_de_passe'] = bcrypt($value);
    }
}
