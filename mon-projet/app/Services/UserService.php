<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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

    public function listReferentsActifsValidated(): Collection
    {
        return $this->userRepository->referentsValidated(true);
    }

    public function listAnciensReferentsValidated(): Collection
    {
        return $this->userRepository->referentsValidated(false);
    }

    public function find(int $id): ?User
    {
        return $this->userRepository->find($id);
    }

    /**
     * Active/désactive un utilisateur (admin).
     */
    public function toggleActif(int $userId): bool
    {
        $user = $this->userRepository->find($userId);
        if (!$user) {
            return false;
        }

        $usersHasActif = Schema::hasTable('users') && Schema::hasColumn('users', 'actif');
        $profilHasActif = Schema::hasTable('profil') && Schema::hasColumn('profil', 'actif');

        $current = 1;
        if ($usersHasActif) {
            $current = (int) ($user->actif ?? 1);
        } elseif ($profilHasActif) {
            $current = (int) (optional($user->profil)->actif ?? 1);
        }

        $next = $current === 1 ? 0 : 1;

        return (bool) DB::transaction(function () use ($user, $next, $usersHasActif, $profilHasActif) {
            $updated = false;

            if ($usersHasActif) {
                $user->actif = $next;
                $updated = $user->save() || $updated;
            }

            if ($profilHasActif) {
                $profil = $user->profil;
                if ($profil) {
                    $profil->actif = $next;
                    $updated = $profil->save() || $updated;
                }
            }

            return $updated;
        });
    }
}
