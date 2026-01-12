<?php

namespace App\Http\Controllers;

use App\Services\AdminService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReferentController extends Controller
{
    public function __construct(private readonly AdminService $adminService)
    {
    }

    /**
     * Affichage de l'espace des référents
     */
    public function index()
    {
        try {
            $beneficiaires = $this->adminService->listAllBeneficiaires();
            $users = $this->adminService->listAllUsers();

            return view('referent.index', [
                'beneficiaires' => $beneficiaires,
                'users' => $users,
                'errorMessage' => null,
            ]);
        } catch (\Throwable $e) {
            Log::error('Erreur lors de la récupération des données : ' . $e->getMessage());

            return view('referent.index', [
                'beneficiaires' => [],
                'users' => [],
                'errorMessage' => "Impossible de récupérer les données pour le moment (problème de connexion à la base de données).",
            ]);
        }
    }
}
