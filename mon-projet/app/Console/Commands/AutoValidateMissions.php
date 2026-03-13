<?php

namespace App\Console\Commands;

use App\Models\CompteRendu;
use App\Models\Mission;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoValidateMissions extends Command
{
    protected $signature = 'missions:auto-validate';
    protected $description = 'Valide automatiquement les missions prises dont l\'heure est dépassée';

    public function handle(): int
    {
        $now = Carbon::now();

        $missions = Mission::where('etat_mission', 'prise')
            ->whereNotNull('benevole_id')
            ->whereNotNull('heure_depart')
            ->get();

        $count = 0;

        foreach ($missions as $mission) {
            $missionStart = Carbon::parse($mission->date_depart)
                ->setTimeFromTimeString(Carbon::parse($mission->heure_depart)->format('H:i:s'));

            if ($now->greaterThan($missionStart)) {
                // Créer le compte rendu s'il n'existe pas encore (pré-remplir les heures)
                CompteRendu::firstOrCreate(
                    ['id_mission' => $mission->id_mission],
                    [
                        'benevole_id'        => $mission->benevole_id,
                        'heure_depart_reel'  => $mission->heure_depart,
                        'heure_arrivee_reel' => $mission->heure_arrivee,
                    ]
                );

                $count++;
            }
        }

        $this->info("$count compte(s) rendu(s) créé(s).");

        return Command::SUCCESS;
    }
}
