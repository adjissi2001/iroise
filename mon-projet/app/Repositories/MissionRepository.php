<?php

namespace App\Repositories;

use App\Models\Mission;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MissionRepository
{
    private function getPivotMissionBeneficiaireColumns(string $pivot): ?array
    {
        if (!Schema::hasTable($pivot)) {
            return null;
        }

        $cols = Schema::getColumnListing($pivot);

        $missionCol = null;
        foreach (['id_mission', 'mission_id'] as $c) {
            if (in_array($c, $cols, true)) {
                $missionCol = $c;
                break;
            }
        }

        $beneficiaireCol = null;
        foreach (['id_beneficiaire', 'beneficiaire_id'] as $c) {
            if (in_array($c, $cols, true)) {
                $beneficiaireCol = $c;
                break;
            }
        }

        if (!$missionCol || !$beneficiaireCol) {
            return null;
        }

        return ['mission' => $missionCol, 'beneficiaire' => $beneficiaireCol];
    }

    private function applyMissionLatestOrder($query, string $table = 'mission')
    {
        // Certains schémas n'ont pas date_creation : fallback sûr.
        if (Schema::hasColumn($table, 'date_creation')) {
            return $query->orderByDesc($table.'.date_creation');
        }

        if (Schema::hasColumn($table, 'created_at')) {
            return $query->orderByDesc($table.'.created_at');
        }

        if (Schema::hasColumn($table, 'id_mission')) {
            return $query->orderByDesc($table.'.id_mission');
        }

        return $query;
    }

    public function listBeneficiairesForSelect(): Collection
    {
        if (!Schema::hasTable('beneficiaire')) {
            return collect();
        }

        $cols = Schema::getColumnListing('beneficiaire');
        $select = array_values(array_filter([
            in_array('id_beneficiaire', $cols, true) ? 'id_beneficiaire' : null,
            in_array('email', $cols, true) ? 'email' : null,
            in_array('nom', $cols, true) ? 'nom' : null,
            in_array('prenom', $cols, true) ? 'prenom' : null,
            in_array('actif', $cols, true) ? 'actif' : null,
        ]));

        // Always include id_beneficiaire
        if (!in_array('id_beneficiaire', $select, true)) {
            $select[] = 'id_beneficiaire';
        }

        $query = DB::table('beneficiaire')->select($select);
        if (in_array('actif', $cols, true)) {
            $query->where('actif', 1);
        }

        if (in_array('email', $cols, true)) {
            $query->orderBy('email');
        } else {
            $query->orderBy('id_beneficiaire');
        }

        return $query->get();
    }

    /** @return int[] */
    public function getBeneficiaireIdsForMission(int $missionId): array
    {
        $pivot = $this->getMissionBeneficiaireTable();
        if ($pivot === null) {
            return [];
        }

        $pivotCols = $this->getPivotMissionBeneficiaireColumns($pivot);
        if ($pivotCols === null) {
            return [];
        }

        return DB::table($pivot)
            ->where($pivotCols['mission'], $missionId)
            ->pluck($pivotCols['beneficiaire'])
            ->map(fn ($v) => (int) $v)
            ->values()
            ->all();
    }

    /**
     * Remplace les associations mission<->beneficiaires.
     * @param int[] $beneficiaireIds
     */
    public function syncMissionBeneficiaires(int $missionId, array $beneficiaireIds): void
    {
        $pivot = $this->getMissionBeneficiaireTable();
        if ($pivot === null) {
            return;
        }

        $pivotCols = $this->getPivotMissionBeneficiaireColumns($pivot);
        if ($pivotCols === null) {
            return;
        }

        $beneficiaireIds = array_values(array_unique(array_map('intval', $beneficiaireIds)));

        $missionCol = $pivotCols['mission'];
        $benefCol = $pivotCols['beneficiaire'];

        // Charge les associations existantes pour éviter de ré-insérer lors d'une édition.
        $existing = DB::table($pivot)
            ->where($missionCol, $missionId)
            ->pluck($benefCol)
            ->map(fn ($v) => (int) $v)
            ->values()
            ->all();
        $existingSet = array_fill_keys($existing, true);

        // Supprime uniquement ce qui a été retiré (au lieu de tout supprimer).
        if ($beneficiaireIds === []) {
            DB::table($pivot)->where($missionCol, $missionId)->delete();
            return;
        }

        DB::table($pivot)
            ->where($missionCol, $missionId)
            ->whereNotIn($benefCol, $beneficiaireIds)
            ->delete();

        $now = now();
        $hasDateCreation = Schema::hasColumn($pivot, 'date_creation');
        $hasDateModification = Schema::hasColumn($pivot, 'date_modification');
        $hasCreatedAt = Schema::hasColumn($pivot, 'created_at');
        $hasUpdatedAt = Schema::hasColumn($pivot, 'updated_at');

        foreach ($beneficiaireIds as $idBeneficiaire) {
            $idBeneficiaire = (int) $idBeneficiaire;

            // Déjà présent -> update uniquement (date_modification / updated_at)
            if (isset($existingSet[$idBeneficiaire])) {
                $update = [];
                if ($hasDateModification) {
                    $update['date_modification'] = $now;
                }
                if ($hasUpdatedAt) {
                    $update['updated_at'] = $now;
                }

                if ($update !== []) {
                    DB::table($pivot)
                        ->where($missionCol, $missionId)
                        ->where($benefCol, $idBeneficiaire)
                        ->update($update);
                }

                continue;
            }

            // Nouveau -> insert
            $row = [
                $missionCol => $missionId,
                $benefCol => $idBeneficiaire,
            ];
            if ($hasDateCreation) {
                $row['date_creation'] = $now;
            }
            if ($hasDateModification) {
                $row['date_modification'] = $now;
            }
            if ($hasCreatedAt) {
                $row['created_at'] = $now;
            }
            if ($hasUpdatedAt) {
                $row['updated_at'] = $now;
            }

            DB::table($pivot)->insert($row);
        }
    }

    private function getMissionBeneficiaireTable(): ?string
    {
        foreach (['mission_beneficiaire', 'mission_beneficiaires'] as $table) {
            if (Schema::hasTable($table)) {
                return $table;
            }
        }

        return null;
    }

    public function allWithCategorie(): Collection
    {
        if (Schema::hasTable('mission')) {
            $query = DB::table('mission')
                ->leftJoin('categorie', 'mission.id_categorie', '=', 'categorie.id_categorie')
                ->select('mission.*', 'categorie.nom_categorie');

            $this->applyMissionLatestOrder($query, 'mission');

            return $query->get();
        }

        return Mission::query()->latest()->get();
    }

    /**
     * @param array{date_depart?:string,categorie?:int|string,etat?:string,q?:string} $filters
     */
    public function paginateWithCategorie(int $perPage = 20, array $filters = []): LengthAwarePaginator
    {
        // legacy join: returns stdClass items
        if (Schema::hasTable('mission')) {
            $query = DB::table('mission')
                ->leftJoin('categorie', 'mission.id_categorie', '=', 'categorie.id_categorie')
                ->select('mission.*', 'categorie.nom_categorie');

            $this->applyMissionLatestOrder($query, 'mission');

            if (!empty($filters['date_depart'])) {
                $query->whereDate('mission.date_depart', $filters['date_depart']);
            }

            if (!empty($filters['categorie'])) {
                $query->where('mission.id_categorie', (int) $filters['categorie']);
            }

            if (!empty($filters['etat'])) {
                $etatColumn = Schema::hasColumn('mission', 'etat_mission') ? 'mission.etat_mission' : 'mission.etat';
                $query->where($etatColumn, $filters['etat']);
            }

            if (!empty($filters['q'])) {
                $term = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], (string) $filters['q']).'%';
                $hasNomLieu = Schema::hasColumn('mission', 'nom_lieu');
                $hasRemarques = Schema::hasColumn('mission', 'remarques');
                $hasCreePar = Schema::hasColumn('mission', 'cree_par');

                $query->where(function ($sub) use ($term, $hasNomLieu, $hasRemarques, $hasCreePar) {
                    if ($hasNomLieu) {
                        $sub->orWhere('mission.nom_lieu', 'like', $term);
                    }
                    if ($hasRemarques) {
                        $sub->orWhere('mission.remarques', 'like', $term);
                    }
                    if ($hasCreePar) {
                        $sub->orWhere('mission.cree_par', 'like', $term);
                    }
                });
            }

            return $query->paginate($perPage);
        }

        // fallback Eloquent
        $query = Mission::query()->latest();
        if (!empty($filters['date_depart'])) {
            $query->whereDate('date_depart', $filters['date_depart']);
        }
        if (!empty($filters['categorie'])) {
            $query->where('id_categorie', (int) $filters['categorie']);
        }
        if (!empty($filters['etat'])) {
            $etatColumn = Schema::hasColumn('missions', 'etat_mission') ? 'etat_mission' : 'etat';
            $query->where($etatColumn, $filters['etat']);
        }
        if (!empty($filters['q'])) {
            $term = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], (string) $filters['q']).'%';
            $query->where(function ($sub) use ($term) {
                $sub->orWhere('nom_lieu', 'like', $term)
                    ->orWhere('lieu', 'like', $term)
                    ->orWhere('remarques', 'like', $term)
                    ->orWhere('description', 'like', $term)
                    ->orWhere('cree_par', 'like', $term);
            });
        }

        return $query->paginate($perPage);
    }

    public function getMissionColumns(): array
    {
        $table = Schema::hasTable('mission') ? 'mission' : 'missions';

        return Schema::getColumnListing($table);
    }

    public function create(array $payload): Mission
    {
        return Mission::create($payload);
    }

    public function update(Mission $mission, array $payload): bool
    {
        return (bool) $mission->update($payload);
    }
}
