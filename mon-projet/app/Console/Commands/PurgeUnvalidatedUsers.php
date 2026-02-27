<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PurgeUnvalidatedUsers extends Command
{
    protected $signature = 'users:purge-unvalidated
                            {--hours=24 : Supprime les comptes non validés plus anciens que X heures}
                            {--dry-run : N\'effectue pas la suppression, affiche uniquement le résultat}
                            {--force : Ne demande pas de confirmation}';

    protected $description = 'Supprime les utilisateurs dont le profil n\'a pas été validé (est_valide=0) après un délai.';

    public function handle(): int
    {
        $hours = (int) $this->option('hours');
        if ($hours <= 0) {
            $this->error('Option --hours invalide (doit être > 0).');
            return self::FAILURE;
        }

        $cutoff = Carbon::now()->subHours($hours);
        $dryRun = (bool) $this->option('dry-run');
        $force = (bool) $this->option('force');

        $query = User::query()
            ->where('is_admin', 0)
            ->where('created_at', '<=', $cutoff)
            ->whereHas('profil', function ($q) {
                $q->where('est_valide', 0);
            });

        $count = (clone $query)->count();

        $this->info(sprintf(
            'Comptes non validés à traiter: %d (seuil: %s, délai: %dh)%s',
            $count,
            $cutoff->toDateTimeString(),
            $hours,
            $dryRun ? ' [DRY-RUN]' : ''
        ));

        if ($count === 0) {
            return self::SUCCESS;
        }

        $sampleLimit = 20;
        $sample = (clone $query)
            ->with('profil')
            ->orderBy('id')
            ->limit($sampleLimit)
            ->get();

        $this->table(
            ['ID', 'Email', 'Rôle', 'Créé le'],
            $sample->map(function (User $user) {
                return [
                    $user->id,
                    $user->email,
                    $user->profil?->role ?? '-',
                    optional($user->created_at)->toDateTimeString(),
                ];
            })->all()
        );

        if ($count > $sampleLimit) {
            $this->line(sprintf('… et %d autre(s).', $count - $sampleLimit));
        }

        if (! $dryRun && ! $force) {
            if (! $this->confirm('Confirmer la suppression des comptes listés ?')) {
                $this->warn('Suppression annulée.');
                return self::SUCCESS;
            }
        }

        $deleted = 0;
        $failed = 0;

        (clone $query)
            ->orderBy('id')
            ->chunkById(200, function ($users) use (&$deleted, &$failed, $dryRun) {
                foreach ($users as $user) {
                    if ($dryRun) {
                        continue;
                    }

                    try {
                        DB::transaction(function () use ($user, &$deleted) {
                            // IMPORTANT: certains environnements DB ont des FK sans cascade.
                            // On supprime/détache donc explicitement les enregistrements liés.
                            $user->voiture()->delete();
                            $user->profil()->delete();
                            $user->beneficiaires()->update(['user_id' => null]);

                            $user->delete();
                            $deleted++;
                        });
                    } catch (\Throwable $e) {
                        $failed++;
                        $this->error(sprintf(
                            'Suppression impossible pour user #%d (%s): %s',
                            $user->id,
                            $user->email ?? '-',
                            $e->getMessage()
                        ));
                    }
                }
            });

        if ($dryRun) {
            $this->info('Dry-run terminé: aucune suppression effectuée.');
            return self::SUCCESS;
        }

        $this->info(sprintf('Suppression terminée: %d compte(s) supprimé(s), %d échec(s).', $deleted, $failed));

        return self::SUCCESS;
    }
}
