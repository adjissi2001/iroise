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
            if (!Schema::hasColumn('beneficiaire', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id_beneficiaire');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beneficiaire', function (Blueprint $table) {
            if (Schema::hasColumn('beneficiaire', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }
};
