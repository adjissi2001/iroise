<?php

namespace Database\Seeders;

use App\Models\Profil;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::factory()->create([
            'email' => 'test@example.com',
            'actif' => true,
        ]);

        Profil::updateOrCreate(
            ['user_id' => $user->id],
            [
                'prenom' => 'Test',
                'nom' => 'User',
                'date_naissance' => '1990-01-01',
                'num_tel' => '0600000000',
                'num_fixe' => null,
                'adresse' => '1 rue de Test',
                'code_postale' => '75001',
                'commune' => null,
                'ville' => 'Paris',
                'role' => 'benevole',
                'est_valide' => 1,
                'actif' => 1,
                'date_creation' => now(),
            ]
        );
    }
}
