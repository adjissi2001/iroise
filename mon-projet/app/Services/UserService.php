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
        return (bool) $authUser->is_admin;
    }

    public function listAll(): Collection
    {
        return $this->userRepository->all();
    }

    public function find(int $id): ?User
    {
        return $this->userRepository->find($id);
    }
}
