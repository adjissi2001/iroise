<?php

namespace App\Repositories;

use App\Models\Profil;

class ProfileRepository
{
    public function deleteByUserId(int $userId): void
    {
        Profil::where('user_id', $userId)->delete();
    }
}
