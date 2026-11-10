<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entretiens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidature_id')->constrained('candidatures')->onDelete('cascade');
            $table->dateTime('date_prevue');
            $table->string('lieu_ou_type', 100)->default('Messagerie instantanée ou Visio');
            $table->enum('statut_entretien', ['programme', 'termine', 'annule', 'absent'])->default('programme');
            $table->text('notes')->nullable();
            $table->timestamp('cree_le')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entretiens');
    }
};