<?php

namespace App\Repositories;

use App\Models\Beneficiaire;
use Illuminate\Support\Collection;

class BeneficiaireRepository
{
    /**
     * Récupère tous les bénéficiaires (usage admin).
     */
    public function all(): Collection
    {
        return Beneficiaire::all();
    }

    /**
     * Récupère tous les bénéficiaires filtrés par statut d'activité.
     */
    public function allByActif(bool $actif): Collection
    {
        return Beneficiaire::where('actif', (int) $actif)->get();
    }

    /**
     * Compte tous les bénéficiaires filtrés par statut d'activité.
     */
    public function countByActif(bool $actif): int
    {
        return Beneficiaire::where('actif', (int) $actif)->count();
    }

    /**
     * Récupère les bénéficiaires appartenant à un utilisateur.
     */
    public function forUser(int $userId): Collection
    {
        return Beneficiaire::where('user_id', $userId)->get();
    }

    /**
     * Trouve un bénéficiaire appartenant à un utilisateur.
     */
    public function findOwned(int $userId, int $beneficiaireId): ?Beneficiaire
    {
        return Beneficiaire::where('user_id', $userId)
            ->where('id_beneficiaire', $beneficiaireId)
            ->first();
    }

    /**
     * Trouve un bénéficiaire par ID (n'importe lequel)
     */
    public function find(int $beneficiaireId): ?Beneficiaire
    {
        return Beneficiaire::where('id_beneficiaire', $beneficiaireId)->first();
    }

    /**
     * Met à jour un bénéficiaire.
     */
    public function update(int $beneficiaireId, array $data): bool
    {
        return Beneficiaire::where('id_beneficiaire', $beneficiaireId)->update($data) > 0;
    }

    /**
     * Supprime un bénéficiaire.
     */
    public function delete(int $beneficiaireId): bool
    {
        return Beneficiaire::where('id_beneficiaire', $beneficiaireId)->delete() > 0;
    }

    /**
     * Crée un bénéficiaire.
     */
    public function create(array $data): Beneficiaire
    {
        return Beneficiaire::create($data);
    }
}
