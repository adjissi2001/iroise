<?php

namespace App\Http\Controllers;

use App\Services\BeneficiaireService;
use Illuminate\Http\Request;

class BeneficiaireController extends Controller
{
    public function __construct(private readonly BeneficiaireService $beneficiaireService)
    {
    }
    /**
     * Affichage de la liste des bénéficiaires
     */
    public function index()
    {
        // Afficher uniquement les bénéficiaires de l'utilisateur connecté
        $beneficiaires = $this->beneficiaireService->listForUser(auth()->user());

        return view('beneficiaire.index', compact('beneficiaires'));
    }

    /**
     * Affichage du détail d’un bénéficiaire
     */
    public function show($id)
    {
        // Vérifier que le bénéficiaire appartient à l'utilisateur connecté
        $beneficiaire = $this->beneficiaireService->findForUser(auth()->user(), (int) $id);

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
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:100'],
            'prenom' => ['required', 'string', 'max:100'],
            'num_tel' => ['nullable', 'string', 'max:20'],
            'date_naissance' => ['required', 'date'],
        ]);

        $updated = $this->beneficiaireService->updateForUser(auth()->user(), (int) $id, $data);

        if (!$updated) {
            return redirect()->back()->with('error', 'Bénéficiaire introuvable.');
        }

        return redirect()->back()->with('success', 'Bénéficiaire mis à jour.');
    }


    /**
     * Supprime un bénéficiaire via requête SQL directe
     */
    public function deleteSql($id)
    {
        $deleted = $this->beneficiaireService->deleteForUser(auth()->user(), (int) $id);

        if (!$deleted) {
            return redirect()->back()->with('error', 'Bénéficiaire introuvable.');
        }

        return redirect()->back()->with('success', 'Bénéficiaire supprimé.');
    }

    /**
     * Ressourceful delete (préféré) — supprime via Eloquent
     */
    public function destroy($id)
    {
        $deleted = $this->beneficiaireService->deleteForUser(auth()->user(), (int) $id);

        if (!$deleted) {
            return redirect()->back()->with('error', 'Bénéficiaire introuvable.');
        }

        return redirect()->back()->with('success', 'Bénéficiaire supprimé.');
    }




    private function authorizeRole()
        {
            $roleProfil = optional(auth()->user()->profil)->role;

            if (!in_array($roleProfil, ['referent', 'benevole'])) {
                abort(403, 'Accès refusé.');
            }
        }

        public function create()
        {
            $this->authorizeRole();
            return view('beneficiaire.create');
        }

        public function store(Request $request)
        {
            $this->authorizeRole();

            $request->validate([
                'nom' => ['required', 'string', 'max:100'],
                'prenom' => ['required', 'string', 'max:100'],
                'date_naissance' => ['required', 'date'],
                'num_tel' => ['nullable', 'string', 'max:20'],
            ]);

            $this->beneficiaireService->createForUser(auth()->user(), [
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'date_naissance' => $request->date_naissance,
                'num_tel' => $request->num_tel,
                'actif' => 1,
            ]);

            return redirect()->route('beneficiaire.index')->with('success', 'Bénéficiaire ajouté avec succès.');
        }

}
