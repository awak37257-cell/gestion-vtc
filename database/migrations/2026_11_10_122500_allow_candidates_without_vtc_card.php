<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidatures', function (Blueprint $table) {
            // Si la colonne n'existe pas, on la crée directement en nullable
            if (!Schema::hasColumn('candidatures', 'numero_carte_vtc')) {
                $table->string('numero_carte_vtc', 50)->nullable();
            } else {
                // Si elle existe, on la modifie pour qu'elle accepte les valeurs nulles
                $table->string('numero_carte_vtc', 50)->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('candidatures', function (Blueprint $table) {
            if (Schema::hasColumn('candidatures', 'numero_carte_vtc')) {
                $table->string('numero_carte_vtc', 50)->nullable(false)->change();
            }
        });
    }
};