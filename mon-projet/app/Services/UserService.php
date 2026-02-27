<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Collection;

class UserService
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    public function canManageUsers(User $authUser): bool
    {
        $roleProfil = optional($authUser->profil)->role;
        return (bool) ($authUser->is_admin || $roleProfil === 'referent');
    }

    public function listAll(): Collection
    {
        return $this->userRepository->all();
    }

    public function listValidated(): Collection
    {
        return $this->userRepository->allValidated();
    }

    public function listPending(): Collection
    {
        return $this->userRepository->allPending();
    }

    public function find(int $id): ?User
    {
        return $this->userRepository->find($id);
    }
}
