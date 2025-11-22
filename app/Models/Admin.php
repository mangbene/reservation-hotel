<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admins';

    protected $fillable = [
        'nom',
        'email',
        'mot_de_passe',
        'role',
        'telephone'
    ];

    // Hash du mot de passe avant enregistrement
    public function setMotDePasseAttribute($value)
    {
        $this->attributes['mot_de_passe'] = bcrypt($value);
    }
}
