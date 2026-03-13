<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mission', function (Blueprint $table) {
            $table->string('commune', 100)->nullable()->after('nom_lieu')->comment('Commune de la mission');
        });
    }

    public function down(): void
    {
        Schema::table('mission', function (Blueprint $table) {
            $table->dropColumn('commune');
        });
    }
};
