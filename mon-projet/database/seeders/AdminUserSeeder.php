<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Profil;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un utilisateur admin par défaut
        $user = User::updateOrCreate(
            ['email' => 'admin@iroise.fr'],
            [
                'email' => 'admin@iroise.fr',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
                'actif' => true,
                'email_verified_at' => now(),
            ]
        );

        // Créer / MAJ le profil associé (nom/prénom affichés partout)
        Profil::updateOrCreate(
            ['user_id' => $user->id],
            [
                'prenom' => 'Admin',
                'nom' => 'Administrateur',
                'date_naissance' => '1970-01-01',
                'num_tel' => null,
                'num_fixe' => null,
                'adresse' => '—',
                'code_postale' => '00000',
                'commune' => null,
                'ville' => '—',
                'role' => 'referent',
                'est_valide' => 1,
                'actif' => 1,
                'date_creation' => now(),
            ]
        );

        $this->command->info('Utilisateur admin créé : admin@iroise.fr / admin123');
    }
}
