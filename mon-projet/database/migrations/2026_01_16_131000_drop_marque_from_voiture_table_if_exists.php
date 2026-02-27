<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('voiture')) {
            return;
        }

        if (!Schema::hasColumn('voiture', 'marque')) {
            return;
        }

        Schema::table('voiture', function (Blueprint $table) {
            $table->dropColumn('marque');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('voiture')) {
            return;
        }

        if (Schema::hasColumn('voiture', 'marque')) {
            return;
        }

        Schema::table('voiture', function (Blueprint $table) {
            $table->string('marque', 100)->nullable();
        });
    }
};
