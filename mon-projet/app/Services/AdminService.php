<?php

namespace App\Services;

use App\Repositories\BeneficiaireRepository;
use Illuminate\Support\Collection;

class AdminService
{
    public function __construct(private readonly BeneficiaireRepository $beneficiaireRepository)
    {
    }

    /**
    * Retourne tous les bénéficiaires pour l'espace admin.
    */
    public function listAllBeneficiaires(): Collection
    {
        return $this->beneficiaireRepository->all();
    }
}
