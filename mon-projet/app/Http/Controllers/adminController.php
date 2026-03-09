<?php
//sadek 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Schema;
use App\Models\Beneficiaire;
use App\Models\User;

// celia  ahmed

class AdminController extends Controller
{
    public function selectBeneficiaires()
    {
        // Vérifie si l'utilisateur est authentifié (géré par middleware auth)
        // Plus besoin de vérification manuelle car le middleware s'en charge

        try {
            // LB/LAB : bénéficiaires actifs / anciens
            $beneficiairesHasActif = Schema::hasTable('beneficiaire') && Schema::hasColumn('beneficiaire', 'actif');

            $lbBeneficiaires = Schema::hasTable('beneficiaire')
                ? ($beneficiairesHasActif ? Beneficiaire::where('actif', 1)->get() : Beneficiaire::all())
                : collect();

            $labBeneficiaires = ($beneficiairesHasActif)
                ? Beneficiaire::where('actif', 0)->get()
                : collect();

            // LR/LAR : référents actifs / anciens
            $referentsBase = User::with('profil')->whereHas('profil', function ($q) {
                $q->where('role', 'referent');
            });

            $usersHasActif = Schema::hasTable('users') && Schema::hasColumn('users', 'actif');
            $profilHasActif = Schema::hasTable('profil') && Schema::hasColumn('profil', 'actif');

            if ($usersHasActif) {
                $lrReferents = (clone $referentsBase)->where('actif', 1)->get();
                $larReferents = (clone $referentsBase)->where('actif', 0)->get();
            } elseif ($profilHasActif) {
                $lrReferents = (clone $referentsBase)->whereHas('profil', function ($q) {
                    $q->where('actif', 1);
                })->get();
                $larReferents = (clone $referentsBase)->whereHas('profil', function ($q) {
                    $q->where('actif', 0);
                })->get();
            } else {
                // Pas de colonne actif => impossible de séparer “anciens” proprement
                $lrReferents = (clone $referentsBase)->get();
                $larReferents = collect();
            }

            return view('admin.administration', [
                'lbBeneficiaires' => $lbBeneficiaires,
                'labBeneficiaires' => $labBeneficiaires,
                'lrReferents' => $lrReferents,
                'larReferents' => $larReferents,
                'errorMessage' => null,
            ]);
        } catch (\Throwable $e) {
            Log::error('Erreur lors de la récupération des membres : ' . $e->getMessage());

            return view('admin.administration', [
                'lbBeneficiaires' => collect(),
                'labBeneficiaires' => collect(),
                'lrReferents' => collect(),
                'larReferents' => collect(),
                'errorMessage' => "Impossible de récupérer les listes pour le moment (problème de connexion à la base de données).",
            ]);
        }
    }
}
