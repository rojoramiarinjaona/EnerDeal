<?php

namespace App\Http\Controllers;

use App\Models\Energie;
use App\Models\Categorie;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EnergieController extends Controller
{
    /**
     * Affiche la liste des énergies du vendeur.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $energies = Energie::where('user_id', Auth::id())
                   ->with('categorie')
                   ->latest()
                   ->get();
        
        return view('vendeur.energies', compact('energies'));
    }

    /**
     * Affiche le formulaire de création d'une énergie.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Categorie::all();
        //dd($categories);
        return view('vendeur.energies.create', compact('categories'));
    }

    /**
     * Stocke une nouvelle énergie.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'stock_kwh' => 'required|numeric|min:0',
            'localisation' => 'required|string|max:255',
            'categorie_id' => 'required|exists:categories,id',
        ]);
        
        $slug = Str::slug($request->titre);
        
        Energie::create([
            'titre' => $request->titre,
            'stock_kwh' => $request->stock_kwh,
            'slug' => $slug,
            'localisation' => $request->localisation,
            'categorie_id' => $request->categorie_id,
            'user_id' => Auth::id(),
        ]);
        
        return redirect()->route('vendeur.energies.index')
            ->with('success', 'Source d\'énergie créée avec succès.');
    }

    /**
     * Affiche les détails d'une énergie.
     */
    public function show(Energie $energie)
    {
        return view('vendeur.energies.show', compact('energie'));
    }

    /**
     * Affiche le formulaire de modification d'une énergie.
     */
    public function edit(Energie $energie)
    {
        $categories = Categorie::all();
        return view('vendeur.energies.edit', compact('energie', 'categories'));
    }

    /**
     * Met à jour une énergie.
     */
    public function update(Request $request, Energie $energie)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:energies,slug,'.$energie->id,
            'stock_kwh' => 'required|numeric|min:0',
            'localisation' => 'required|string|max:255',
            'categorie_id' => 'required|exists:categories,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $energie->update($validated);

        return redirect()->route('vendeur.energies.show', ['energie' => $energie])
            ->with('success', 'Énergie mise à jour avec succès');
    }

    /**
     * Supprime une énergie.
     */
    public function destroy(Energie $energie)
    {
        // Vérifier s'il existe des formules utilisant cette énergie
        if ($energie->formules()->count() > 0) {
            return redirect()->route('vendeur.energies.show', ['energie' => $energie])
                ->with('error', 'Impossible de supprimer cette énergie car elle est utilisée par des formules');
        }

        $energie->delete();

        return redirect()->route('vendeur.energies.index')
            ->with('success', 'Énergie supprimée avec succès');
    }
}
