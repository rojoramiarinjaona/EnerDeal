<?php

namespace App\Http\Controllers;

use App\Models\Formule;
use App\Models\Energie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FormuleController extends Controller
{
    /**
     * Affiche la liste des formules pour les vendeurs.
     */
    public function index()
    {
        $formules = Formule::where('user_id', Auth::id())
                   ->with('energie')
                   ->latest()
                   ->get();
        
        return view('vendeur.formules.index', compact('formules'));
    }

    /**
     * Affiche le formulaire de création d'une formule.
     */
    public function create()
    {
        $energies = Energie::where('user_id', Auth::id())->get();
        
        if ($energies->isEmpty()) {
            return redirect()->route('vendeur.energies.index')
                ->with('error', 'Vous devez créer au moins une énergie avant de créer une formule.');
        }
        
        return view('vendeur.formules.create', compact('energies'));
    }

    /**
     * Enregistre une nouvelle formule.
     */
    public function store(Request $request)
    {
        $request->validate([
            'intitule' => 'required|string|max:255',
            'quantite_kwh' => 'required|numeric|min:1',
            'duree' => 'required|string|max:255',
            'taxite' => 'required|numeric|min:0',
            'prix_kwh' => 'required|numeric|min:0.01',
            'details_contrat' => 'required|string',
            'conditions_resiliation' => 'required|string',
            'modalite_livraison' => 'required|string',
            'energie_id' => 'required|exists:energies,id',
        ]);
        
        // Vérifier que l'énergie appartient au vendeur
        $energie = Energie::findOrFail($request->energie_id);
        if ($energie->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Cette énergie ne vous appartient pas.');
        }
        
        // Générer une référence unique
        $reference = 'FORM-' . strtoupper(Str::random(6));
        
        Formule::create([
            'ref' => $reference,
            'intitule' => $request->intitule,
            'quantite_kwh' => $request->quantite_kwh,
            'duree' => $request->duree,
            'taxite' => $request->taxite,
            'prix_kwh' => $request->prix_kwh,
            'details_contrat' => $request->details_contrat,
            'conditions_resiliation' => $request->conditions_resiliation,
            'modalite_livraison' => $request->modalite_livraison,
            'energie_id' => $request->energie_id,
            'user_id' => Auth::id(),
        ]);
        
        return redirect()->route('vendeur.formules.index')
            ->with('success', 'Formule créée avec succès.');
    }

    /**
     * Affiche les détails d'une formule.
     */
    public function show(Formule $formule)
    {
        // Vérifier que la formule appartient au vendeur
        if ($formule->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            abort(403);
        }
        
        $formule->load('energie');
        
        return view('vendeur.formules.show', compact('formule'));
    }

    /**
     * Affiche le formulaire d'édition d'une formule.
     */
    public function edit(Formule $formule)
    {
        // Vérifier que la formule appartient au vendeur
        if ($formule->user_id !== Auth::id()) {
            abort(403);
        }
        
        $energies = Energie::where('user_id', Auth::id())->get();
        
        return view('vendeur.formules.edit', compact('formule', 'energies'));
    }

    /**
     * Met à jour une formule.
     */
    public function update(Request $request, Formule $formule)
    {
        // Vérifier que la formule appartient au vendeur
        if ($formule->user_id !== Auth::id()) {
            abort(403);
        }
        
        $request->validate([
            'intitule' => 'required|string|max:255',
            'quantite_kwh' => 'required|numeric|min:1',
            'duree' => 'required|string|max:255',
            'taxite' => 'required|numeric|min:0',
            'prix_kwh' => 'required|numeric|min:0.01',
            'details_contrat' => 'required|string',
            'conditions_resiliation' => 'required|string',
            'modalite_livraison' => 'required|string',
            'energie_id' => 'required|exists:energies,id',
        ]);
        
        // Vérifier que l'énergie appartient au vendeur
        $energie = Energie::findOrFail($request->energie_id);
        if ($energie->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Cette énergie ne vous appartient pas.');
        }
        
        $formule->update([
            'intitule' => $request->intitule,
            'quantite_kwh' => $request->quantite_kwh,
            'duree' => $request->duree,
            'taxite' => $request->taxite,
            'prix_kwh' => $request->prix_kwh,
            'details_contrat' => $request->details_contrat,
            'conditions_resiliation' => $request->conditions_resiliation,
            'modalite_livraison' => $request->modalite_livraison,
            'energie_id' => $request->energie_id,
        ]);
        
        return redirect()->route('vendeur.formules.index')
            ->with('success', 'Formule mise à jour avec succès.');
    }

    /**
     * Supprime une formule.
     */
    public function destroy(Formule $formule)
    {
        // Vérifier que la formule appartient au vendeur
        if ($formule->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Vérifier si la formule est liée à des contrats
        if ($formule->contrats()->exists()) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer cette formule car elle est liée à des contrats.');
        }
        
        $formule->delete();
        
        return redirect()->route('vendeur.formules.index')
            ->with('success', 'Formule supprimée avec succès.');
    }
    
    /**
     * Affiche la liste des formules pour le client
     */
    public function clientIndex()
    {
        $formules = Formule::with(['energie', 'energie.categorie'])->get();
        return view('client.formules.index', compact('formules'));
    }

    /**
     * Affiche les détails d'une formule pour le client
     */
    public function clientShow(Formule $formule)
    {
        $formule->load(['energie', 'energie.categorie']);
        return view('client.formules.show', compact('formule'));
    }
}
