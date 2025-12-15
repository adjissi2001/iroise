<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function selectBeneficiaires()
    {
        // Vérifie si l'admin est connecté
        if (!session('admin')) {
            return redirect('/login')->withErrors(['auth' => 'Accès refusé.']);
        }

        // 🔹 Requête SQL pour récupérer les bénéficiaires
        $beneficiaires = DB::select('SELECT * FROM beneficiaire');

        // 🔹 Envoi des résultats à la vue
        return view('admin.administration', ['beneficiaires' => $beneficiaires]);
    }
}
