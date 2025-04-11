<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('formules', function (Blueprint $table) {
            $table->id();
            $table->string('ref')->unique();
            $table->string('intitule');
            $table->double('quantite_kwh');
            $table->string('duree');
            $table->double('taxite');
            $table->double('prix_kwh');
            $table->text('details_contrat');
            $table->text('conditions_resiliation');
            $table->text('modalite_livraison');
            $table->foreignId('energie_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Vendeur
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formules');
    }
}; 