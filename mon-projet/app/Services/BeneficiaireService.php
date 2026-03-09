<?php

namespace App\Services;

use App\Models\Beneficiaire;
use App\Repositories\BeneficiaireRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;

class BeneficiaireService
{
    public function __construct(private readonly BeneficiaireRepository $repository)
    {
    }

    /**
     * Liste les bénéficiaires pour un utilisateur donné.
     */
    public function listForUser(Authenticatable $user): Collection
    {
        return $this->repository->forUser($user->id);
    }

    /**
     * Liste TOUS les bénéficiaires
     */
    public function listAll(): Collection
    {
        return $this->repository->all();
    }

    /**
     * Liste TOUS les bénéficiaires filtrés par statut d'activité.
     */
    public function listAllByActif(bool $actif): Collection
    {
        return $this->repository->allByActif($actif);
    }

    /**
     * Compte TOUS les bénéficiaires filtrés par statut d'activité.
     */
    public function countAllByActif(bool $actif): int
    {
        return $this->repository->countByActif($actif);
    }

    /**
     * Retourne un bénéficiaire appartenant à l'utilisateur, ou null.
     */
    public function findForUser(Authenticatable $user, int $beneficiaireId): ?Beneficiaire
    {
        return $this->repository->findOwned($user->id, $beneficiaireId);
    }

    /**
     * Retourne un bénéficiaire par ID (n'importe lequel)
     */
    public function findById(int $beneficiaireId): ?Beneficiaire
    {
        return $this->repository->find($beneficiaireId);
    }

    /**
     * Met à jour un bénéficiaire appartenant à l'utilisateur.
     */
    public function updateForUser(Authenticatable $user, int $beneficiaireId, array $data): bool
    {
        $beneficiaire = $this->findForUser($user, $beneficiaireId);
        if (!$beneficiaire) {
            return false;
        }

        return $this->repository->update($beneficiaireId, $data);
    }

    /**
     * Supprime un bénéficiaire appartenant à l'utilisateur.
     */
    public function deleteForUser(Authenticatable $user, int $beneficiaireId): bool
    {
        $beneficiaire = $this->findForUser($user, $beneficiaireId);
        if (!$beneficiaire) {
            return false;
        }

        return $this->repository->delete($beneficiaireId);
    }

    /**
     * Crée un bénéficiaire pour l'utilisateur.
     */
    public function createForUser(Authenticatable $user, array $data): Beneficiaire
    {
        $payload = array_merge($data, [
            'user_id' => $user->id,
        ]);

        return $this->repository->create($payload);
    }
}
