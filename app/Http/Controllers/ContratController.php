<?php

namespace App\Http\Controllers;

use App\Models\Contrat;
use App\Models\Formule;
use App\Models\Facture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ContratController extends Controller
{
    /**
     * Affiche la liste des contrats pour les vendeurs.
     */
    public function index()
    {
        $user = Auth::user();
        $formules = Formule::where('user_id', $user->id)->pluck('id');
        
        $contrats = Contrat::whereIn('formule_id', $formules)
                  ->with('client', 'formule')
                  ->latest()
                  ->paginate(10);
        
        return view('vendeur.contrats.index', compact('contrats'));
    }

    /**
     * Affiche les détails d'un contrat pour le vendeur.
     */
    public function show(Contrat $contrat)
    {
        $user = Auth::user();
        $formule = $contrat->formule;
        
        // Vérifier que le contrat est lié à une formule du vendeur
        if ($formule->user_id !== $user->id && !$user->hasRole('admin')) {
            abort(403);
        }
        
        $contrat->load('client', 'formule.energie', 'factures', 'declarationIncidents');
        
        return view('vendeur.contrats.show', compact('contrat'));
    }

    /**
     * Affiche le formulaire d'édition d'un contrat.
     */
    public function edit(Contrat $contrat)
    {
        $user = Auth::user();
        $formule = $contrat->formule;
        
        // Vérifier que le contrat est lié à une formule du vendeur
        if ($formule->user_id !== $user->id && !$user->hasRole('admin')) {
            abort(403);
        }
        
        $contrat->load('client', 'formule');
        
        return view('vendeur.contrats.edit', compact('contrat'));
    }

    /**
     * Met à jour le contrat.
     */
    public function update(Request $request, Contrat $contrat)
    {
        $user = Auth::user();
        $formule = $contrat->formule;
        
        // Vérifier que le contrat est lié à une formule du vendeur
        if ($formule->user_id !== $user->id && !$user->hasRole('admin')) {
            abort(403);
        }
        
        $validatedData = $request->validate([
            'statut' => 'required|in:en attente,actif,résilié',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'montant_total' => 'sometimes|numeric|min:0',
        ]);
        
        $contrat->update($validatedData);
        
        return redirect()->route('vendeur.contrats.show', $contrat)
            ->with('success', 'Contrat mis à jour avec succès.');
    }

    /**
     * Affiche la liste des contrats pour le client.
     */
    public function clientIndex()
    {
        $contrats = Contrat::where('user_id', Auth::id())
                          ->with(['formule', 'formule.energie'])
                          ->get();
        return view('client.contrats.index', compact('contrats'));
    }

    /**
     * Affiche les détails d'un contrat pour le client.
     */
    public function clientShow(Contrat $contrat)
    {
        if ($contrat->user_id !== Auth::id()) {
            abort(403);
        }
        return view('client.contrats.show', compact('contrat'));
    }

    /**
     * Crée un nouveau contrat à partir du panier du client.
     */
    public function clientStore(Request $request)
    {
        $request->validate([
            'formule_ids' => 'required|array',
            'formule_ids.*' => 'exists:formules,id'
        ]);
        
        DB::beginTransaction();
        
        try {
            foreach($request->formule_ids as $formule_id) {
                $formule = Formule::findOrFail($formule_id);
                
                // Extraire le nombre de mois de la durée
                $duree = intval(preg_replace('/[^0-9]/', '', $formule->duree));
                
                // Création du contrat
                $dateDebut = now();
                $contrat = Contrat::create([
                    'date_debut' => $dateDebut,
                    'date_fin' => $dateDebut->copy()->addMonths($duree),
                    'statut' => 'actif',
                    'formule_id' => $formule->id,
                    'user_id' => Auth::id()
                ]);
                
                // Création de la facture associée
                $montant = $formule->quantite_kwh * $formule->prix_kwh * (1 + $formule->taxite / 100);
                
                Facture::create([
                    'montant' => $montant,
                    'date_paiement' => null,
                    'statut_paiement' => 'en_attente',
                    'contrat_id' => $contrat->id,
                    'user_id' => Auth::id(),
                    'formule_id' => $formule->id
                ]);
            }
            
            // Vider le panier après la signature des contrats
            session()->forget('panier');
            
            DB::commit();
            
            return redirect()->route('client.contrats.index')
                ->with('success', 'Contrat(s) signé(s) avec succès. Vous pouvez consulter vos factures.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la signature du contrat : ' . $e->getMessage());
        }
    }

    public function download(Contrat $contrat)
    {
        // Vérifier que l'utilisateur a le droit d'accéder à ce contrat
        if (auth()->user()->hasRole('client') && $contrat->user_id !== auth()->id()) {
            abort(403);
        }

        if (auth()->user()->hasRole('vendeur') && $contrat->formule->user_id !== auth()->id()) {
            abort(403);
        }

        $pdf = PDF::loadView('client.contrats.pdf', compact('contrat'));
        
        return $pdf->download('contrat-' . $contrat->id . '.pdf');
    }
}
