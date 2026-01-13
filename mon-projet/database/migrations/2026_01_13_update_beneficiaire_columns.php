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
        Schema::table('beneficiaire', function (Blueprint $table) {
            // Agrandir la colonne moyen_paiement
            $table->string('moyen_paiement', 50)->nullable()->change();
            
            // S'assurer que les autres colonnes existent
            if (!Schema::hasColumn('beneficiaire', 'age')) {
                $table->integer('age')->nullable();
            }
            if (!Schema::hasColumn('beneficiaire', 'num_fixe')) {
                $table->string('num_fixe', 20)->nullable();
            }
            if (!Schema::hasColumn('beneficiaire', 'contact_urgence')) {
                $table->string('contact_urgence', 100)->nullable();
            }
            if (!Schema::hasColumn('beneficiaire', 'tel_contact_urgence')) {
                $table->string('tel_contact_urgence', 20)->nullable();
            }
            if (!Schema::hasColumn('beneficiaire', 'montant_cotisation')) {
                $table->decimal('montant_cotisation', 10, 2)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beneficiaire', function (Blueprint $table) {
            // On ne fait rien au rollback
        });
    }
};
