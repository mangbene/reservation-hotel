<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $table = 'reservations';

    // Colonnes autorisées à être remplies via create/update
    protected $fillable = [
        'client_id',
        'chambre_id',
        'date_arrivee',
        'date_depart',
        'prix_total',
        'statut'
    ];

    // Relation avec le client
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // Relation avec la chambre
    public function chambre()
    {
        return $this->belongsTo(Chambre::class);
    }
}
