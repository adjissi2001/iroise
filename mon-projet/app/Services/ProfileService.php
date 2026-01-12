<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\ProfileRepository;
use Illuminate\Support\Facades\DB;

class ProfileService
{
    public function __construct(private readonly ProfileRepository $profileRepository)
    {
    }

    public function updateUser(User $user, array $data): void
    {
        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
    }

    public function deleteUser(User $user): void
    {
        DB::transaction(function () use ($user) {
            $this->profileRepository->deleteByUserId($user->id);
            $user->delete();
        });
    }
}
