<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function selectBeneficiaires()
    {
        // Vérifie si l'admin est connecté
        if (!session('admin')) {
            return redirect('/login')->withErrors(['auth' => 'Accès refusé.']);
        }

        try {
            // 🔹 Requête SQL pour récupérer les bénéficiaires
            $beneficiaires = DB::select('SELECT * FROM beneficiaire');
            return view('admin.administration', [
                'beneficiaires' => $beneficiaires,
                'errorMessage' => null,
            ]);
        } catch (\Throwable $e) {
            // 🚨 En cas d'erreur de connexion ou requête, journaliser et afficher un message clair
            Log::error('Erreur lors de la récupération des bénéficiaires : ' . $e->getMessage());

            return view('admin.administration', [
                'beneficiaires' => [],
                'errorMessage' => "Impossible de récupérer la liste des bénéficiaires pour le moment (problème de connexion à la base de données).",
            ]);
        }
    }
}
