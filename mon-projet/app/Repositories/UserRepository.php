<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Collection;

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

    public function find(int $id): ?User
    {
        return User::with(['profil', 'beneficiaires', 'voiture'])->find($id);
    }
}
