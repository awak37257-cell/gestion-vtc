<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidatures', function (Blueprint $table) {
            // Lien personnel pour renvoyer des documents : on ne garde que l'empreinte (hash) du jeton
            $table->string('jeton_correction', 64)->nullable()->unique()->after('motif_rejet');
            $table->timestamp('jeton_correction_expire_le')->nullable()->after('jeton_correction');
        });

        Schema::table('documents_candidature', function (Blueprint $table) {
            // Date à laquelle le candidat a renvoyé ce document
            $table->timestamp('renvoye_le')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('documents_candidature', function (Blueprint $table) {
            $table->dropColumn('renvoye_le');
        });

        Schema::table('candidatures', function (Blueprint $table) {
            $table->dropUnique(['jeton_correction']);
            $table->dropColumn(['jeton_correction', 'jeton_correction_expire_le']);
        });
    }
};