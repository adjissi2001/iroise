<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class AdminCategorieController extends Controller
{
    public function index()
    {
        $categories = collect();

        if (Schema::hasTable('categorie')) {
            $select = ['id_categorie', 'nom_categorie'];
            if (Schema::hasColumn('categorie', 'description')) {
                $select[] = 'description';
            }

            $categories = DB::table('categorie')
                ->select($select)
                ->orderBy('id_categorie')
                ->get();
        }

        return view('admin.categories.index', compact('categories'));
    }

    public function edit($id)
    {
        if (!Schema::hasTable('categorie')) {
            return redirect()->route('admin.categories.index')
                ->with('error', "La table 'categorie' n'existe pas dans la base.");
        }

        $categorie = DB::table('categorie')
            ->where('id_categorie', $id)
            ->first();

        if (!$categorie) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Catégorie introuvable.');
        }

        $hasDescription = Schema::hasColumn('categorie', 'description');

        return view('admin.categories.edit', compact('categorie', 'hasDescription'));
    }

    public function store(Request $request)
    {
        if (!Schema::hasTable('categorie')) {
            return redirect()->route('admin.categories.index')
                ->with('error', "La table 'categorie' n'existe pas dans la base.");
        }

        $validated = $request->validate([
            'nom_categorie' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categorie', 'nom_categorie'),
            ],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $payload = [
            'nom_categorie' => $validated['nom_categorie'],
        ];

        if (Schema::hasColumn('categorie', 'description')) {
            $payload['description'] = $validated['description'] ?? null;
        }

        DB::table('categorie')->insert($payload);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie créée avec succès.');
    }

    public function update(Request $request, $id)
    {
        if (!Schema::hasTable('categorie')) {
            return redirect()->route('admin.categories.index')
                ->with('error', "La table 'categorie' n'existe pas dans la base.");
        }

        $existing = DB::table('categorie')->where('id_categorie', $id)->first();
        if (!$existing) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Catégorie introuvable.');
        }

        $rules = [
            'nom_categorie' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categorie', 'nom_categorie')->ignore($id, 'id_categorie'),
            ],
        ];

        if (Schema::hasColumn('categorie', 'description')) {
            $rules['description'] = ['nullable', 'string', 'max:500'];
        }

        $validated = $request->validate($rules);

        $payload = [
            'nom_categorie' => $validated['nom_categorie'],
        ];

        if (Schema::hasColumn('categorie', 'description')) {
            $payload['description'] = $validated['description'] ?? null;
        }

        DB::table('categorie')
            ->where('id_categorie', $id)
            ->update($payload);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie mise à jour avec succès.');
    }

    public function destroy($id)
    {
        if (!Schema::hasTable('categorie')) {
            return redirect()->route('admin.categories.index')
                ->with('error', "La table 'categorie' n'existe pas dans la base.");
        }

        $deleted = DB::table('categorie')
            ->where('id_categorie', $id)
            ->delete();

        if (!$deleted) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Catégorie introuvable ou déjà supprimée.');
        }

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie supprimée avec succès.');
    }
}
