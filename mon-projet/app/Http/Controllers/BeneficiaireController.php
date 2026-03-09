<?php
//123 viva l'algerie 
namespace App\Http\Controllers;



/// celia modif ahmed

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
    public function index(Request $request)
    {
        $segment = strtoupper((string) $request->query('segment', 'LB'));
        if (!in_array($segment, ['LB', 'LAB'], true)) {
            $segment = 'LB';
        }

        $showActif = $segment === 'LB';

        $beneficiaires = $this->beneficiaireService->listAllByActif($showActif);
        $countLB = $this->beneficiaireService->countAllByActif(true);
        $countLAB = $this->beneficiaireService->countAllByActif(false);

        return view('beneficiaire.index', compact('beneficiaires', 'segment', 'countLB', 'countLAB'));
    }

    /**
     * Affichage du détail d’un bénéficiaire
     */
    public function show($id)
    {
        // Afficher n'importe quel bénéficiaire
        $beneficiaire = $this->beneficiaireService->findById((int) $id);

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

    /**
     * Active/désactive un bénéficiaire (admin/référent).
     */
    public function toggleActif(Request $request, $id)
    {
        $user = auth()->user();
        $beneficiaireId = (int) $id;

        $beneficiaire = $this->beneficiaireService->findById($beneficiaireId);
        if (!$beneficiaire) {
            return redirect()->back()->with('error', 'Bénéficiaire introuvable.');
        }

        $current = (int) ($beneficiaire->actif ?? 1);
        $nextIsActive = $current !== 1;

        $roleProfil = optional($user?->profil)->role;

        if ($user && ((bool) ($user->is_admin ?? false) || $roleProfil === 'referent')) {
            $ok = $this->beneficiaireService->toggleActif($beneficiaireId);
        } else {
            $ok = $this->beneficiaireService->toggleForUser($user, $beneficiaireId);
        }

        if (!$ok) {
            return redirect()->back()->with('error', 'Action non autorisée ou bénéficiaire introuvable.');
        }

        return redirect()->back()->with(
            'success',
            $nextIsActive ? 'Bénéficiaire activé.' : 'Bénéficiaire désactivé.'
        );
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
                'email' => ['nullable', 'email'],
                'num_tel' => ['nullable', 'string', 'max:20'],
                'num_fixe' => ['nullable', 'string', 'max:20'],
                'contact_urgence' => ['nullable', 'string', 'max:100'],
                'tel_contact_urgence' => ['nullable', 'string', 'max:20'],
                'montant_cotisation' => ['nullable', 'numeric', 'min:0'],
                'moyen_paiement' => ['nullable', 'string', 'max:50'],
            ]);

            // Calculer l'âge à partir de la date de naissance
            $dateNaissance = \Carbon\Carbon::parse($request->date_naissance);
            $age = $dateNaissance->age;

            $this->beneficiaireService->createForUser(auth()->user(), [
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'date_naissance' => $request->date_naissance,
                'age' => $age,
                'email' => $request->email,
                'num_tel' => $request->num_tel,
                'num_fixe' => $request->num_fixe,
                'contact_urgence' => $request->contact_urgence,
                'tel_contact_urgence' => $request->tel_contact_urgence,
                'montant_cotisation' => $request->montant_cotisation,
                'moyen_paiement' => $request->moyen_paiement,
                'actif' => 1,
            ]);

            return redirect()->route('beneficiaire.index')->with('success', 'Bénéficiaire ajouté avec succès.');
        }

}
