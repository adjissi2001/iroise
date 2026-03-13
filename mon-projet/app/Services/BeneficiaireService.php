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
        $roleProfil = optional($user?->profil)->role;
        
        // Admin et référent peuvent modifier n'importe quel bénéficiaire
        if ((bool)($user->is_admin ?? false) || in_array($roleProfil, ['referent', 'benevole'])) {
            $beneficiaire = $this->findById($beneficiaireId);
        } else {
            // Bénévole simple : seulement ses propres bénéficiaires
            $beneficiaire = $this->findForUser($user, $beneficiaireId);
        }

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
        // Empêche la suppression si le rôle est bénévole
        $roleProfil = optional($user->profil)->role;
        if ($roleProfil === 'benevole') {
            return false;
        }

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

    /**
     * Active/désactive un bénéficiaire (usage admin/référent).
     */
    public function toggleActif(int $beneficiaireId): bool
    {
        $beneficiaire = $this->findById($beneficiaireId);
        if (!$beneficiaire) {
            return false;
        }

        $current = (int) ($beneficiaire->actif ?? 1);
        $next = $current === 1 ? 0 : 1;

        return $this->repository->update($beneficiaireId, ['actif' => $next]);
    }

    /**
     * Active/désactive un bénéficiaire appartenant à l'utilisateur.
     */
    public function toggleForUser(Authenticatable $user, int $beneficiaireId): bool
    {
        $beneficiaire = $this->findForUser($user, $beneficiaireId);
        if (!$beneficiaire) {
            return false;
        }

        $current = (int) ($beneficiaire->actif ?? 1);
        $next = $current === 1 ? 0 : 1;

        return $this->repository->update($beneficiaireId, ['actif' => $next]);
    }
}
