<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Beneficiaire;

class BeneficiaireController extends Controller
{
    /**
     * Affichage de la liste des bénéficiaires
     */
    public function index()
    {
        // On peut utiliser Eloquent pour plus de clarté
        $beneficiaires = Beneficiaire::all();

        return view('beneficiaire.index', compact('beneficiaires'));
    }

    /**
     * Affichage du détail d’un bénéficiaire
     */
    public function show($id)
    {
        $beneficiaire = Beneficiaire::where('id_beneficiaire', $id)->first();

        if (!$beneficiaire) {
            return redirect()->back()->with('error', 'Bénéficiaire introuvable.');
        }

        return view('beneficiaire.show', compact('beneficiaire'));
    }


    /**
     * Met à jour un bénéficiaire via requête SQL directe
     * (pas de page d'édition)
     */
    public function updateSql(Request $request, $id)
    {
        // Exemple de données à mettre à jour (adapter selon tes champs)
        $b = Beneficiaire::where('id_beneficiaire', $id)->first();
        if (!$b) {
            return redirect()->back()->with('error', 'Bénéficiaire introuvable.');
        }

        $b->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'num_tel' => $request->num_tel,
            'date_naissance' => $request->date_naissance,
        ]);

        return redirect()->back()->with('success', 'Bénéficiaire mis à jour.');
    }


    /**
     * Supprime un bénéficiaire via requête SQL directe
     */
    public function deleteSql($id)
    {
        // Garder pour compatibilité : suppression via DB
        DB::table('beneficiaire')
            ->where('id_beneficiaire', $id)
            ->delete();

        return redirect()->back()->with('success', 'Bénéficiaire supprimé.');
    }

    /**
     * Ressourceful delete (préféré) — supprime via Eloquent
     */
    public function destroy($id)
    {
        $b = Beneficiaire::where('id_beneficiaire', $id)->first();
        if (!$b) {
            return redirect()->back()->with('error', 'Bénéficiaire introuvable.');
        }

        $b->delete();

        return redirect()->back()->with('success', 'Bénéficiaire supprimé.');
    }
}
