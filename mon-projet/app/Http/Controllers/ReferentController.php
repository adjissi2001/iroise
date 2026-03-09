<?php

namespace App\Http\Controllers;

use App\Services\AdminService;
use App\Services\BeneficiaireService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReferentController extends Controller
{
    public function __construct(
        private readonly AdminService $adminService,
        private readonly BeneficiaireService $beneficiaireService,
    )
    {
    }

    /**
     * Affichage de l'espace des référents
     */
    public function index()
    {
        try {
            $request = request();

            $segment = strtoupper((string) $request->query('segment', 'LB'));
            if (!in_array($segment, ['LB', 'LAB'], true)) {
                $segment = 'LB';
            }

            $showActif = $segment === 'LB';
            $beneficiaires = $this->beneficiaireService->listAllByActif($showActif);
            $countLB = $this->beneficiaireService->countAllByActif(true);
            $countLAB = $this->beneficiaireService->countAllByActif(false);

            $users = $this->adminService->listAllUsers();

            return view('referent.index', [
                'beneficiaires' => $beneficiaires,
                'segment' => $segment,
                'countLB' => $countLB,
                'countLAB' => $countLAB,
                'users' => $users,
                'errorMessage' => null,
            ]);
        } catch (\Throwable $e) {
            Log::error('Erreur lors de la récupération des données : ' . $e->getMessage());

            return view('referent.index', [
                'beneficiaires' => [],
                'segment' => 'LB',
                'countLB' => 0,
                'countLAB' => 0,
                'users' => [],
                'errorMessage' => "Impossible de récupérer les données pour le moment (problème de connexion à la base de données).",
            ]);
        }
    }
}
