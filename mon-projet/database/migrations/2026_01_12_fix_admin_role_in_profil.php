<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mettre à jour le rôle dans la table profil pour les utilisateurs admins
        // On se base sur l'email admin@iroise.fr pour identifier l'admin
        $adminUser = DB::table('users')->where('email', 'admin@iroise.fr')->first();
        
        if ($adminUser) {
            DB::table('profil')
                ->where('user_id', $adminUser->id)
                ->update(['role' => 'admin']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optionnel: remettre en benevole si rollback
        $adminUser = DB::table('users')->where('email', 'admin@iroise.fr')->first();
        
        if ($adminUser) {
            DB::table('profil')
                ->where('user_id', $adminUser->id)
                ->update(['role' => 'benevole']);
        }
    }
};
