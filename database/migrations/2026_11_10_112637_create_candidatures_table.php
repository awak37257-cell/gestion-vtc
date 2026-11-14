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
    
    // Informations administratives et critères obligatoires
    $table->string('numero_permis', 50);
    $table->boolean('permis_b_valide')->default(false);
    $table->string('permis_b_fichier'); // Copie du permis
    
    $table->integer('annees_experience'); // Minimum 3 ans requis[cite: 1]
    
    $table->boolean('possede_cacr')->default(false); // Certificat d'Aptitude (facultatif)[cite: 1]
    $table->string('cacr_fichier')->nullable();
    
    $table->string('statut_casier'); // 'disponible' ou 'en_cours'
    $table->string('casier_fichier')->nullable(); // Bulletin n°3[cite: 1]

    // Suivi et gestion de la candidature
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
