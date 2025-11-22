<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('chambre_id')->constrained('chambres')->onDelete('cascade');
            $table->date('date_arrivee');
            $table->date('date_depart');
            $table->decimal('prix_total', 10, 2)->nullable();
            $table->enum('statut', ['attente', 'confirme', 'annule'])->default('attente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
