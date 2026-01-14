<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('profil')) {
            return;
        }

        Schema::create('profil', function (Blueprint $table) {
            $table->bigIncrements('profil_id');

            $table->unsignedBigInteger('user_id');
            $table->string('nom', 100);
            $table->string('prenom', 100);
            $table->date('date_naissance');

            $table->string('num_tel', 20)->nullable();
            $table->string('num_fixe', 20)->nullable();

            $table->string('adresse', 255);
            $table->string('code_postale', 10);
            $table->string('commune', 100)->nullable();
            $table->string('ville', 100);

            $table->enum('role', ['referent', 'benevole', 'bienfaiteur']);
            $table->tinyInteger('est_valide')->default(0);
            $table->tinyInteger('actif')->default(1);

            $table->timestamp('date_creation')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        // On ne drop pas automatiquement en down pour éviter des pertes de données
        // si la table existait déjà avant la migration.
    }
};
