<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private readonly UserService $userService)
    {
    }

    /**
     * Affichage de la liste des utilisateurs
     */
    public function index()
    {
        $authUser = auth()->user();
        if (!$this->userService->canManageUsers($authUser)) {
            return redirect()->back()->with('error', 'Accès non autorisé.');
        }

        $users = $this->userService->listAll();

        return view('user.index', compact('users'));
    }

    /**
     * Affichage du détail d'un utilisateur
     */
    public function show($id)
    {
        $authUser = auth()->user();
        if (!$this->userService->canManageUsers($authUser)) {
            return redirect()->back()->with('error', 'Accès non autorisé.');
        }

        $user = $this->userService->find((int) $id);

        if (!$user) {
            abort(404);
        }

        return view('user.show', compact('user'));
    }
}
