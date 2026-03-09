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
        $hasCreatedAt = Schema::hasColumn('users', 'created_at');
        $hasUpdatedAt = Schema::hasColumn('users', 'updated_at');

        if ($hasCreatedAt && $hasUpdatedAt) {
            return;
        }

        Schema::table('users', function (Blueprint $table) use ($hasCreatedAt, $hasUpdatedAt) {
            if (!$hasCreatedAt) {
                $table->timestamp('created_at')->nullable();
            }

            if (!$hasUpdatedAt) {
                $table->timestamp('updated_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $hasCreatedAt = Schema::hasColumn('users', 'created_at');
        $hasUpdatedAt = Schema::hasColumn('users', 'updated_at');

        if (!$hasCreatedAt && !$hasUpdatedAt) {
            return;
        }

        Schema::table('users', function (Blueprint $table) use ($hasCreatedAt, $hasUpdatedAt) {
            $columnsToDrop = [];

            if ($hasCreatedAt) {
                $columnsToDrop[] = 'created_at';
            }

            if ($hasUpdatedAt) {
                $columnsToDrop[] = 'updated_at';
            }

            if ($columnsToDrop !== []) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
