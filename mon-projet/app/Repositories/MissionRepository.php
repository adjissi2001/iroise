<?php

namespace App\Repositories;

use App\Models\Mission;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MissionRepository
{
    public function allWithCategorie(): Collection
    {
        if (Schema::hasTable('mission')) {
            return DB::table('mission')
                ->leftJoin('categorie', 'mission.id_categorie', '=', 'categorie.id_categorie')
                ->select('mission.*', 'categorie.nom_categorie')
                ->orderByDesc('date_creation')
                ->get();
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
                ->select('mission.*', 'categorie.nom_categorie')
                ->orderByDesc('date_creation');

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
