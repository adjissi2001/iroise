<?php

namespace App\Http\Controllers;

use App\Services\AdminService;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function __construct(private readonly AdminService $adminService)
    {
    }

    public function selectBeneficiaires()
    {
        try {
            $beneficiaires = $this->adminService->listAllBeneficiaires();

            return view('admin.administration', [
                'beneficiaires' => $beneficiaires,
                'errorMessage' => null,
            ]);
        } catch (\Throwable $e) {
            Log::error('Erreur lors de la récupération des benéficiaires : ' . $e->getMessage());

            return view('admin.administration', [
                'beneficiaires' => [],
                'errorMessage' => "impossible de récupérer la liste des bénéficiaires pour le moment (problème de connexion à la base de données).",
            ]);
        }
    }
}
