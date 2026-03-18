<?php

namespace App\Http\Controllers;

use App\Services\Statistique;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StatisticsController extends Controller
{
    /**
     * Affiche les statistiques (missions par année, kilométrage, etc.)
     */
    public function index(): View
    {
        $statistique = (new Statistique())->calculer();

        // Stats par mois (année en cours)
        $anneeActuelle = date('Y');
        $statsMois = $this->getMissionsByMonth($anneeActuelle);

        // Stats par année
        $statsAnnee = $this->getMissionsByYear();

        // Missions non prises
        $missionsNonPrises = $this->getMissionsNonPrises();

        return view('admin.statistics.index', array_merge(
            $statistique->toArray(),
            [
                'statsMois' => $statsMois,
                'statsAnnee' => $statsAnnee,
                'anneeActuelle' => $anneeActuelle,
                'missionsNonPrisesData' => $missionsNonPrises,
            ]
        ));
    }

    /**
     * Récupère les missions et kilométrage par mois
     */
    private function getMissionsByMonth($annee): array
    {
        $missions = DB::table('mission')
            ->where(DB::raw('YEAR(date_depart)'), $annee)
            ->where('etat_mission', '!=', 'annulee')
            ->whereNotNull('heure_depart')
            ->whereNotNull('heure_arrivee')
            ->select('date_depart', 'kilometrage', 'heure_depart', 'heure_arrivee')
            ->orderBy('date_depart')
            ->get();

        $moisNames = [
            1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
            9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
        ];

        // Grouper par mois et calculer les totaux
        $statsByMonth = [];
        foreach ($missions as $m) {
            $mois = (int) date('n', strtotime($m->date_depart));
            
            if (!isset($statsByMonth[$mois])) {
                $statsByMonth[$mois] = [
                    'total' => 0,
                    'km' => 0,
                    'minutes' => 0,
                ];
            }

            $statsByMonth[$mois]['total']++;
            $statsByMonth[$mois]['km'] += (int)$m->kilometrage;

            // Calculer les heures
            $depart  = strtotime($m->heure_depart);
            $arrivee = strtotime($m->heure_arrivee);
            if ($depart !== false && $arrivee !== false && $arrivee > $depart) {
                $statsByMonth[$mois]['minutes'] += ($arrivee - $depart) / 60;
            }
        }

        $result = [];
        for ($i = 1; $i <= 12; $i++) {
            if (isset($statsByMonth[$i])) {
                $heures = intdiv((int)$statsByMonth[$i]['minutes'], 60);
                $minutes = (int)$statsByMonth[$i]['minutes'] % 60;
                $result[] = [
                    'mois' => $moisNames[$i],
                    'total' => $statsByMonth[$i]['total'],
                    'km' => $statsByMonth[$i]['km'],
                    'heures' => $heures,
                    'minutes' => $minutes,
                ];
            }
        }

        return $result;
    }

    /**
     * Récupère les missions et kilométrage par année
     */
    private function getMissionsByYear(): array
    {
        $missions = DB::table('mission')
            ->where('etat_mission', '!=', 'annulee')
            ->whereNotNull('heure_depart')
            ->whereNotNull('heure_arrivee')
            ->select('date_depart', 'kilometrage', 'heure_depart', 'heure_arrivee')
            ->orderBy('date_depart')
            ->get();

        // Grouper par année et calculer les totaux
        $statsByYear = [];
        foreach ($missions as $m) {
            $annee = (int) date('Y', strtotime($m->date_depart));
            
            if (!isset($statsByYear[$annee])) {
                $statsByYear[$annee] = [
                    'total' => 0,
                    'km' => 0,
                    'minutes' => 0,
                ];
            }

            $statsByYear[$annee]['total']++;
            $statsByYear[$annee]['km'] += (int)$m->kilometrage;

            // Calculer les heures
            $depart  = strtotime($m->heure_depart);
            $arrivee = strtotime($m->heure_arrivee);
            if ($depart !== false && $arrivee !== false && $arrivee > $depart) {
                $statsByYear[$annee]['minutes'] += ($arrivee - $depart) / 60;
            }
        }

        $result = [];
        foreach ($statsByYear as $annee => $data) {
            $heures = intdiv((int)$data['minutes'], 60);
            $minutes = (int)$data['minutes'] % 60;
            $result[] = [
                'annee' => $annee,
                'total' => $data['total'],
                'km' => $data['km'],
                'heures' => $heures,
                'minutes' => $minutes,
            ];
        }

        // Trier par année décroissante
        usort($result, function($a, $b) {
            return $b['annee'] <=> $a['annee'];
        });

        return $result;
    }

    /**
     * Récupère les missions non prises
     */
    private function getMissionsNonPrises(): array
    {
        $missions = DB::table('mission')
            ->where('mission.etat_mission', '!=', 'prise')
            ->where('mission.etat_mission', '!=', 'validee')
            ->where('mission.etat_mission', '!=', 'annulee')
            ->select('mission.id_mission', 'mission.nom_lieu', 'mission.date_depart', 'mission.etat_mission', 'mission.kilometrage')
            ->orderBy('mission.date_depart', 'DESC')
            ->get();

        return $missions->map(function($m) {
            return [
                'id' => $m->id_mission,
                'lieu' => $m->nom_lieu,
                'date' => $m->date_depart,
                'etat' => $m->etat_mission,
                'km' => $m->kilometrage ?? 0,
            ];
        })->toArray();
    }
}
