<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'adresse')) {
                $table->dropColumn('adresse');
            }
            if (Schema::hasColumn('users', 'ville')) {
                $table->dropColumn('ville');
            }
            if (Schema::hasColumn('users', 'code_postal')) {
                $table->dropColumn('code_postal');
            }
            if (Schema::hasColumn('users', 'num_fixe')) {
                $table->dropColumn('num_fixe');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'adresse')) {
                $table->string('adresse', 255)->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'ville')) {
                $table->string('ville', 100)->nullable()->after('adresse');
            }
            if (!Schema::hasColumn('users', 'code_postal')) {
                $table->string('code_postal', 20)->nullable()->after('ville');
            }
            if (!Schema::hasColumn('users', 'num_fixe')) {
                $table->string('num_fixe', 20)->nullable()->after('code_postal');
            }
        });
    }
};
