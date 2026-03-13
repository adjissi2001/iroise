<?php

namespace App\Services;

use App\Models\Mission;
use App\Repositories\MissionRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MissionService
{
    public function __construct(private readonly MissionRepository $repository)
    {
    }

    public function listAllForAdmin(): Collection
    {
        return $this->repository->allWithCategorie();
    }

    /**
     * @param array{date_depart?:string,categorie?:int|string,etat?:string,q?:string} $filters
     */
    public function listForAdmin(int $perPage = 20, array $filters = []): LengthAwarePaginator
    {
        return $this->repository->paginateWithCategorie($perPage, $filters);
    }

    public function listCategories(): Collection
    {
        if (!Schema::hasTable('categorie')) {
            return collect();
        }

        return DB::table('categorie')->select('id_categorie', 'nom_categorie')->get();
    }

    public function listBeneficiairesForSelect(): Collection
    {
        return $this->repository->listBeneficiairesForSelect();
    }

    /** @return int[] */
    public function getBeneficiaireIdsForMission(Mission $mission): array
    {
        return $this->repository->getBeneficiaireIdsForMission((int) $mission->getKey());
    }

    /**
     * Mappe les champs du formulaire vers le schéma legacy `mission`.
     */
    public function buildLegacyPayload(array $data, ?Authenticatable $user): array
    {
        $payload = [];

        // lieu -> nom_lieu
        if (array_key_exists('lieu', $data) && $data['lieu'] !== null) {
            if (Schema::hasColumn('mission', 'nom_lieu') || Schema::hasColumn('missions', 'nom_lieu')) {
                $payload['nom_lieu'] = $data['lieu'];
            } else {
                $payload['lieu'] = $data['lieu'];
            }
        }

        // commune
        if (array_key_exists('commune', $data)) {
            $payload['commune'] = $data['commune'];
        }

        // description -> remarques
        if (array_key_exists('description', $data) && $data['description'] !== null) {
            if (Schema::hasColumn('mission', 'remarques') || Schema::hasColumn('missions', 'remarques')) {
                $payload['remarques'] = $data['description'];
            } else {
                $payload['description'] = $data['description'];
            }
        }

        // dates/times - Ne pas inclure si null
        if (!empty($data['date_depart'])) {
            $payload['date_depart'] = $data['date_depart'];
        }
        if (!empty($data['heure_depart'])) {
            $payload['heure_depart'] = $data['heure_depart'];
        }
        if (!empty($data['heure_arrivee'])) {
            $payload['heure_arrivee'] = $data['heure_arrivee'];
        }

        // default state
        if (Schema::hasColumn('mission', 'etat_mission') || Schema::hasColumn('missions', 'etat_mission')) {
            $payload['etat_mission'] = $data['etat'] ?? 'non_prise';
        } else {
            $payload['etat'] = $data['etat'] ?? 'non_prise';
        }

        // creator
        if ($user) {
            if (Schema::hasColumn('mission', 'cree_par') || Schema::hasColumn('missions', 'cree_par')) {
                $payload['cree_par'] = $user->email;
            } elseif (Schema::hasColumn('missions', 'referent_id')) {
                $payload['referent_id'] = $user->id;
            }
        }

        // categorie: id -> id_categorie (preferred)
        $catId = $data['categorie'] ?? null;
        if ($catId) {
            if (Schema::hasColumn('mission', 'id_categorie') || Schema::hasColumn('missions', 'id_categorie')) {
                $payload['id_categorie'] = $catId;
            } else {
                $catName = DB::table('categorie')->where('id_categorie', $catId)->value('nom_categorie');
                if ($catName && (Schema::hasColumn('mission', 'categorie') || Schema::hasColumn('missions', 'categorie'))) {
                    $payload['categorie'] = $catName;
                }
            }
        }

        return $this->filterPayloadToExistingColumns($payload);
    }

    public function createForAdmin(?Authenticatable $user, array $validatedData): Mission
    {
        $payload = $this->buildLegacyPayload($validatedData, $user);

        $mission = $this->repository->create($payload);
        $beneficiaires = $validatedData['beneficiaires'] ?? [];
        if (is_array($beneficiaires)) {
            $this->repository->syncMissionBeneficiaires((int) $mission->getKey(), $beneficiaires);
        }

        return $mission;
    }

    public function updateForAdmin(?Authenticatable $user, Mission $mission, array $validatedData): bool
    {
        $payload = $this->buildLegacyPayload($validatedData, $user);

        $updated = $this->repository->update($mission, $payload);
        $beneficiaires = $validatedData['beneficiaires'] ?? [];
        if (is_array($beneficiaires)) {
            $this->repository->syncMissionBeneficiaires((int) $mission->getKey(), $beneficiaires);
        }

        return $updated;
    }

    public function cancelMission(Mission $mission): bool
    {
        $column = Schema::hasColumn('mission', 'etat_mission') || Schema::hasColumn('missions', 'etat_mission')
            ? 'etat_mission'
            : 'etat';

        return $this->repository->update($mission, [$column => 'annulee']);
    }

    private function filterPayloadToExistingColumns(array $payload): array
    {
        $cols = $this->repository->getMissionColumns();

        return Arr::only($payload, $cols);
    }
}
