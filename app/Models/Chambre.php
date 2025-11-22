<?php
// fichier : app/Models/Chambre.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chambre extends Model
{
    protected $table = 'chambres'; // nom exact de la table

    protected $fillable = [
        'numero', 'type', 'capacite', 'prix', 'description', 'statut'
    ];
}
