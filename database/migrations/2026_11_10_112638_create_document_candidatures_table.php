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
        Schema::create('documents_candidature', function (Blueprint $table) {
    $table->id();
    $table->foreignId('candidature_id')->constrained('candidatures')->onDelete('cascade');
    $table->enum('type_document', [
        'permis_recto', 'permis_verso', 
        'carte_vtc_recto', 'carte_vtc_verso', 
        'piece_identite', 'visite_medicale', 'casier_judiciaire'
    ]);
    $table->string('chemin_fichier');
    $table->boolean('est_valide')->nullable();
    $table->string('note_rejet', 255)->nullable();
    $table->timestamp('cree_le')->useCurrent();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_candidatures');
    }
};
