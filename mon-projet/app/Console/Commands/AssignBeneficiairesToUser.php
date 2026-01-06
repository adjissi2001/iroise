<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Beneficiaire;
use App\Models\User;

class AssignBeneficiairesToUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'beneficiaires:assign {user_id? : ID de l\'utilisateur (optionnel)} {--force : Forcer l\'assignation sans confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assigner tous les bénéficiaires sans user_id à un utilisateur';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');

        // Si aucun user_id n'est fourni, afficher la liste des utilisateurs
        if (!$userId) {
            $users = User::all();
            
            if ($users->isEmpty()) {
                $this->error('Aucun utilisateur trouvé dans la base de données.');
                return 1;
            }

            $this->info('Liste des utilisateurs disponibles:');
            $this->table(
                ['ID', 'Nom', 'Email'],
                $users->map(fn($u) => [$u->id, $u->name, $u->email])
            );

            $userId = $this->ask('Entrez l\'ID de l\'utilisateur auquel assigner les bénéficiaires');
        }

        // Vérifier que l'utilisateur existe
        $user = User::find($userId);
        if (!$user) {
            $this->error("Utilisateur avec l'ID $userId introuvable.");
            return 1;
        }

        // Récupérer les bénéficiaires sans user_id
        $beneficiaires = Beneficiaire::whereNull('user_id')->get();

        if ($beneficiaires->isEmpty()) {
            $this->info('Aucun bénéficiaire sans assignation trouvé.');
            return 0;
        }

        $this->info("Trouvé {$beneficiaires->count()} bénéficiaire(s) sans assignation.");
        
        if ($this->option('force') || $this->confirm("Voulez-vous assigner ces bénéficiaires à {$user->name} ({$user->email})?", true)) {
            $count = 0;
            foreach ($beneficiaires as $beneficiaire) {
                $beneficiaire->user_id = $user->id;
                $beneficiaire->save();
                $count++;
            }

            $this->info("✅ {$count} bénéficiaire(s) ont été assignés à {$user->name}!");
        } else {
            $this->info('Opération annulée.');
        }

        return 0;
    }
}
