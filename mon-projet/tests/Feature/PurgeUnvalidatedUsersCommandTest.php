<?php

use App\Models\Profil;
use App\Models\User;
use Carbon\Carbon;

it('purges unvalidated users older than the cutoff (when forced)', function () {
    Carbon::setTestNow(Carbon::parse('2026-02-01 12:00:00'));

    $oldUnvalidated = User::factory()->create([
        'created_at' => now()->subHours(25),
        'updated_at' => now()->subHours(25),
        'is_admin' => false,
    ]);

    Profil::create([
        'user_id' => $oldUnvalidated->id,
        'nom' => 'Test',
        'prenom' => 'Old',
        'date_naissance' => '2000-01-01',
        'adresse' => '1 rue de test',
        'code_postale' => '29200',
        'ville' => 'Brest',
        'role' => 'benevole',
        'est_valide' => 0,
        'actif' => 1,
    ]);

    $oldValidated = User::factory()->create([
        'created_at' => now()->subHours(25),
        'updated_at' => now()->subHours(25),
        'is_admin' => false,
    ]);

    Profil::create([
        'user_id' => $oldValidated->id,
        'nom' => 'Test',
        'prenom' => 'Validated',
        'date_naissance' => '2000-01-01',
        'adresse' => '1 rue de test',
        'code_postale' => '29200',
        'ville' => 'Brest',
        'role' => 'benevole',
        'est_valide' => 1,
        'actif' => 1,
    ]);

    $recentUnvalidated = User::factory()->create([
        'created_at' => now()->subHours(10),
        'updated_at' => now()->subHours(10),
        'is_admin' => false,
    ]);

    Profil::create([
        'user_id' => $recentUnvalidated->id,
        'nom' => 'Test',
        'prenom' => 'Recent',
        'date_naissance' => '2000-01-01',
        'adresse' => '1 rue de test',
        'code_postale' => '29200',
        'ville' => 'Brest',
        'role' => 'benevole',
        'est_valide' => 0,
        'actif' => 1,
    ]);

    $this->artisan('users:purge-unvalidated --hours=24 --force')
        ->assertExitCode(0);

    expect(User::find($oldUnvalidated->id))->toBeNull();
    expect(User::find($oldValidated->id))->not->toBeNull();
    expect(User::find($recentUnvalidated->id))->not->toBeNull();
});
