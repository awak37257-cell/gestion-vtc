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
        Schema::create('candidatures', function (Blueprint $table) {
    $table->id();
    $table->foreignId('chauffeur_id')->constrained('chauffeurs')->onDelete('cascade');
    $table->string('numero_permis', 50);
    $table->integer('annees_permis');
    $table->string('numero_carte_vtc', 50);
    $table->enum('statut', ['en_attente', 'en_cours_examen', 'rejete', 'accepte', 'entretien_programme'])->default('en_attente');
    $table->text('motif_rejet')->nullable();
    $table->unsignedBigInteger('examine_par')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidatures');
    }
};
