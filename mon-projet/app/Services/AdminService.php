<?php

namespace App\Services;

use App\Repositories\BeneficiaireRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Collection;

class AdminService
{
    public function __construct(
        private readonly BeneficiaireRepository $beneficiaireRepository,
        private readonly UserRepository $userRepository
    )
    {
    }

    /**
    * Retourne tous les bénéficiaires pour l'espace admin.
    */
    public function listAllBeneficiaires(): Collection
    {
        return $this->beneficiaireRepository->all();
    }

    /**
    * Retourne tous les utilisateurs pour l'espace admin/référent.
    */
    public function listAllUsers(): Collection
    {
        return $this->userRepository->all();
    }
}
