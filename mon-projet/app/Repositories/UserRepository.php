<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class UserRepository
{
    public function all(): Collection
    {
        return User::with('profil')->get();
    }

    public function allValidated(): Collection
    {
        return User::with('profil')->whereHas('profil', function ($q) {
            $q->where('est_valide', 1);
        })->get();
    }

    public function allPending(): Collection
    {
        return User::with('profil')->whereHas('profil', function ($q) {
            $q->where('est_valide', 0);
        })->get();
    }

    /**
     * Référents validés (profil.role = 'referent' AND profil.est_valide = 1)
     * Optionnellement filtrés par actif/inactif.
     */
    public function referentsValidated(?bool $active = null): Collection
    {
        $query = User::with('profil')->whereHas('profil', function ($q) {
            $q->where('role', 'referent')->where('est_valide', 1);
        });

        if ($active === null) {
            return $query->get();
        }

        $usersHasActif = Schema::hasTable('users') && Schema::hasColumn('users', 'actif');
        $profilHasActif = Schema::hasTable('profil') && Schema::hasColumn('profil', 'actif');

        $value = $active ? 1 : 0;

        if ($usersHasActif) {
            $query->where('actif', $value);
            return $query->get();
        }

        if ($profilHasActif) {
            $query->whereHas('profil', function ($q) use ($value) {
                $q->where('actif', $value);
            });
            return $query->get();
        }

        // Pas de colonne actif => impossible de distinguer les anciens.
        return $active ? $query->get() : collect();
    }

    public function find(int $id): ?User
    {
        return User::with(['profil', 'beneficiaires', 'voiture'])->find($id);
    }
}
