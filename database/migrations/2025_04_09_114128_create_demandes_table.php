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
        Schema::create('demandes', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default('en_attente');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Utilisateur faisant la demande
            $table->timestamps();
        });

        // Table pivot pour la relation many-to-many entre demandes et formules
        Schema::create('demande_formule', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demande_id')->constrained()->onDelete('cascade');
            $table->foreignId('formule_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demande_formule');
        Schema::dropIfExists('demandes');
    }
};
