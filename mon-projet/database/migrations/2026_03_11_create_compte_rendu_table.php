<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compte_rendu', function (Blueprint $table) {
            $table->id('id_compte_rendu');
            $table->unsignedBigInteger('id_mission');
            $table->unsignedBigInteger('benevole_id');
            $table->string('kilometrage', 20)->nullable();
            $table->time('heure_depart_reel')->nullable();
            $table->time('heure_arrivee_reel')->nullable();
            $table->text('remarques_problemes')->nullable();
            $table->timestamps();

            $table->foreign('id_mission')->references('id_mission')->on('mission')->onDelete('cascade');
            $table->foreign('benevole_id')->references('id')->on('users')->onDelete('cascade');

            $table->unique('id_mission');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compte_rendu');
    }
};
