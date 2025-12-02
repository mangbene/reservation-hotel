<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $table = 'reservations';

    protected $fillable = [
        'client_id',
        'chambre_id',
        'date_arrivee',
        'date_depart',
        'prix_total',
        'statut'
    ];

    // Relation avec le client (User)
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    // Relation avec la chambre
    public function chambre()
    {
        return $this->belongsTo(Chambre::class);
    }

    // Scopes utiles
    public function scopeActive($query)
    {
        return $query->whereIn('statut', ['confirme', 'attente']);
    }

    public function scopeFuture($query)
    {
        return $query->where('date_arrivee', '>=', now());
    }
}