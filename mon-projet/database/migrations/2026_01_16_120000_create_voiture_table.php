<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('voiture')) {
            return;
        }

        Schema::create('voiture', function (Blueprint $table) {
            $table->increments('id_voiture');
            $table->string('num_immatriculation', 50);
            $table->string('marque', 100)->nullable();
            $table->integer('puissance_voiture')->nullable();

            $table->unsignedBigInteger('user_id');
            $table->index('user_id', 'fk_voiture_user');
            $table->foreign('user_id', 'fk_voiture_user')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voiture');
    }
};
