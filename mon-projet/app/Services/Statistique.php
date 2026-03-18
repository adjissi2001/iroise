<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Objet dédié au calcul des statistiques du tableau de bord.
 * Récupère les données depuis les tables mission et compte_rendu.
 */
class Statistique
{
    public int $totalMissions     = 0;
    public int $missionsValidees  = 0;
    public int $missionsEnCours   = 0;
    public int $missionsAnnulees  = 0;
    public int $missionsNonPrises = 0;
    public float $totalKilometrage = 0;
    public int $totalHeures       = 0;
    public int $totalMinutes      = 0;

    public function calculer(): self
    {
        $this->calculerStatsMissions();
        $this->calculerStatsCompteRendu();

        return $this;
    }

    /**
     * Statistiques issues de la table mission.
     */
    private function calculerStatsMissions(): void
    {
        if (!Schema::hasTable('mission')) {
            return;
        }

        $etatCol = Schema::hasColumn('mission', 'etat_mission') ? 'etat_mission' : 'etat';

        $this->totalMissions    = DB::table('mission')->count();
        $this->missionsValidees = DB::table('mission')->where($etatCol, 'validee')->count();
        $this->missionsEnCours  = DB::table('mission')->where($etatCol, 'prise')->count();
        $this->missionsAnnulees = DB::table('mission')->where($etatCol, 'annulee')->count();
        $this->missionsNonPrises = $this->totalMissions - ($this->missionsValidees + $this->missionsEnCours + $this->missionsAnnulees);

        // Kilométrage (missions non annulées)
        if (Schema::hasColumn('mission', 'kilometrage')) {
            $this->totalKilometrage = (float) DB::table('mission')
                ->where($etatCol, '!=', 'annulee')
                ->sum('kilometrage');
        }

        // Heures de bénévolat calculées depuis heure_depart / heure_arrivee
        if (Schema::hasColumn('mission', 'heure_depart') && Schema::hasColumn('mission', 'heure_arrivee')) {
            $missions = DB::table('mission')
                ->where($etatCol, '!=', 'annulee')
                ->whereNotNull('heure_depart')
                ->whereNotNull('heure_arrivee')
                ->select('heure_depart', 'heure_arrivee')
                ->get();

            $totalMinutesAll = 0;
            foreach ($missions as $m) {
                $depart  = strtotime($m->heure_depart);
                $arrivee = strtotime($m->heure_arrivee);

                if ($depart !== false && $arrivee !== false && $arrivee > $depart) {
                    $totalMinutesAll += ($arrivee - $depart) / 60;
                }
            }

            $this->totalHeures  = intdiv((int) $totalMinutesAll, 60);
            $this->totalMinutes = (int) $totalMinutesAll % 60;
        }
    }

    /**
     * Statistiques complémentaires issues de la table compte_rendu (si elle existe).
     * Les colonnes typiques attendues : kilometrage, duree, nb_heures, etc.
     */
    private function calculerStatsCompteRendu(): void
    {
        // Détecte dynamiquement le nom de la table
        $table = null;
        foreach (['compte_rendu', 'compte_rendus', 'compterendu'] as $candidate) {
            if (Schema::hasTable($candidate)) {
                $table = $candidate;
                break;
            }
        }

        if ($table === null) {
            return;
        }

        $cols = Schema::getColumnListing($table);

        // Kilométrage additionnel depuis compte_rendu
        foreach (['kilometrage', 'km', 'distance'] as $kmCol) {
            if (in_array($kmCol, $cols, true)) {
                $this->totalKilometrage += (float) DB::table($table)->sum($kmCol);
                break;
            }
        }

        // Heures de bénévolat additionnelles depuis compte_rendu
        foreach (['duree', 'nb_heures', 'heures', 'duree_minutes'] as $dureeCol) {
            if (in_array($dureeCol, $cols, true)) {
                $valeur = (float) DB::table($table)->sum($dureeCol);

                // Si la colonne contient des minutes (duree_minutes), convertir
                if (str_contains($dureeCol, 'minute')) {
                    $this->totalHeures  += intdiv((int) $valeur, 60);
                    $this->totalMinutes += (int) $valeur % 60;
                } else {
                    // Sinon on considère que c'est en heures (ou heures décimales)
                    $heuresEntieres = (int) floor($valeur);
                    $minutesFraction = (int) round(($valeur - $heuresEntieres) * 60);
                    $this->totalHeures  += $heuresEntieres;
                    $this->totalMinutes += $minutesFraction;
                }

                // Normaliser les minutes > 60
                $this->totalHeures  += intdiv($this->totalMinutes, 60);
                $this->totalMinutes  = $this->totalMinutes % 60;
                break;
            }
        }
    }

    /**
     * Pourcentage d'un état par rapport au total.
     */
    public function pourcentage(int $count): int
    {
        return $this->totalMissions > 0 ? (int) round($count / $this->totalMissions * 100) : 0;
    }

    /**
     * Retourne les stats sous forme de tableau (pour passage à la vue).
     */
    public function toArray(): array
    {
        return [
            'statistique'       => $this,
            'totalMissions'     => $this->totalMissions,
            'missionsValidees'  => $this->missionsValidees,
            'missionsEnCours'   => $this->missionsEnCours,
            'missionsAnnulees'  => $this->missionsAnnulees,
            'missionsNonPrises' => $this->missionsNonPrises,
            'totalKilometrage'  => $this->totalKilometrage,
            'totalHeures'       => $this->totalHeures,
            'totalMinutes'      => $this->totalMinutes,
        ];
    }
}
