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

    public function find(int $id): ?User
    {
        return User::find($id);
    }
}
