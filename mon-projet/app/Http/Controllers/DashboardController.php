<?php

namespace App\Http\Controllers;

use App\Services\Statistique;

class DashboardController extends Controller
{
    public function index()
    {
        $statistique = (new Statistique())->calculer();

        return view('dashboard', $statistique->toArray());
    }
}
