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
        Schema::create('declaration_incidents', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('details');
            $table->integer('niveau');
            $table->string('statut')->default('ouvert');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Client
            $table->foreignId('contrat_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('declaration_incidents');
    }
};
