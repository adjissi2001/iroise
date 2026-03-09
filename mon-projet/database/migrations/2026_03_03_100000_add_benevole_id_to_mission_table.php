<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ajout de benevole_id sur la table mission
 * pour savoir quel b├®n├®vole a pris la mission.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mission', function (Blueprint $table) {
            $table->unsignedBigInteger('benevole_id')->nullable()->after('etat_mission');

            $table->foreign('benevole_id')
                  ->references('id')
                  ->on('users')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('mission', function (Blueprint $table) {
            $table->dropForeign(['benevole_id']);
            $table->dropColumn('benevole_id');
        });
    }
};