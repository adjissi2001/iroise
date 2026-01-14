<?php

namespace App\Services;

use App\Models\Profil;
use App\Models\User;
use App\Repositories\ProfileRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ProfileService
{
    public function __construct(private readonly ProfileRepository $profileRepository)
    {
    }

    public function updateUser(User $user, array $data): void
    {
        $user->fill(Arr::only($data, ['email']));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        DB::transaction(function () use ($user, $data) {
            $user->save();

            $profil = $user->profil;
            if (!$profil) {
                $profil = new Profil([
                    'user_id' => $user->id,
                    'role' => 'benevole',
                    'est_valide' => 0,
                    'actif' => 1,
                    'date_creation' => now(),
                ]);
            }

            $profil->fill(Arr::only($data, [
                'prenom',
                'nom',
                'date_naissance',
                'num_tel',
                'num_fixe',
                'adresse',
                'code_postale',
                'commune',
                'ville',
            ]));
            $profil->save();
        });
    }

    public function deleteUser(User $user): void
    {
        DB::transaction(function () use ($user) {
            $this->profileRepository->deleteByUserId($user->id);
            $user->delete();
        });
    }
}
