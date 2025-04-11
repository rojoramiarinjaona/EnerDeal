<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\Formule;
use App\Models\Contrat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class FactureController extends Controller
{
    /**
     * Affiche la liste des factures pour les vendeurs.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Récupérer les factures liées aux formules du vendeur
        $factures = Facture::whereHas('formule', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with('client', 'contrat', 'formule')
        ->latest()
        ->paginate(15);
        
        // Préparer les statistiques
        $stats = [
            'total' => $factures->total(),
            'payees' => Facture::whereHas('formule', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('statut_paiement', 'payée')->count(),
            'en_attente' => Facture::whereHas('formule', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('statut_paiement', 'en attente')->count(),
            'en_retard' => Facture::whereHas('formule', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('statut_paiement', 'en retard')->count(),
        ];
        
        return view('vendeur.factures.index', compact('factures', 'stats'));
    }

    /**
     * Affiche les détails d'une facture pour le vendeur.
     */
    public function show(Facture $facture)
    {
        $user = Auth::user();
        
        // Vérifier que la facture est liée à une formule du vendeur
        if ($facture->formule->user_id !== $user->id) {
            abort(403);
        }
        
        $facture->load('client', 'contrat', 'formule.energie');
        
        return view('vendeur.factures.show', compact('facture'));
    }

    /**
     * Met à jour le statut de paiement d'une facture (pour les vendeurs).
     */
    public function update(Request $request, Facture $facture)
    {
        $user = Auth::user();
        
        // Vérifier que la facture est liée à une formule du vendeur
        if ($facture->formule->user_id !== $user->id) {
            abort(403);
        }
        
        $request->validate([
            'statut_paiement' => 'required|in:en_attente,payé,annulé',
            'date_paiement' => 'nullable|date',
        ]);
        
        $data = [
            'statut_paiement' => $request->statut_paiement,
        ];
        
        if ($request->statut_paiement === 'payé' && $request->filled('date_paiement')) {
            $data['date_paiement'] = $request->date_paiement;
        } elseif ($request->statut_paiement !== 'payé') {
            $data['date_paiement'] = null;
        }
        
        $facture->update($data);
        
        return redirect()->back()
            ->with('success', 'Statut de la facture mis à jour avec succès.');
    }

    /**
     * Crée une nouvelle facture pour un contrat existant (pour les vendeurs).
     */
    public function store(Request $request)
    {
        $request->validate([
            'contrat_id' => 'required|exists:contrats,id',
            'montant_ht' => 'required|numeric|min:0',
            'taux_tva' => 'required|numeric|min:0',
        ]);
        
        $contrat = Contrat::findOrFail($request->contrat_id);
        
        // Vérifier que le contrat est lié à une formule du vendeur
        $user = Auth::user();
        if ($contrat->formule->user_id !== $user->id) {
            abort(403);
        }
        
        // Calculer le montant TTC
        $montantTTC = $request->montant_ht * (1 + $request->taux_tva / 100);
        
        Facture::create([
            'montant' => $request->montant_ht,
            'statut_paiement' => 'en attente',
            'contrat_id' => $contrat->id,
            'user_id' => $contrat->user_id,
            'formule_id' => $contrat->formule_id,
        ]);
        
        return redirect()->route('vendeur.factures.index')
            ->with('success', 'Facture créée avec succès.');
    }

    /**
     * Affiche la liste des factures pour le client.
     */
    public function clientIndex()
    {
        $factures = Facture::where('user_id', Auth::id())
                  ->with('contrat', 'formule')
                  ->latest()
                  ->paginate(10);
        
        return view('client.factures.index', compact('factures'));
    }

    /**
     * Affiche les détails d'une facture pour le client.
     */
    public function clientShow(Facture $facture)
    {
        // Vérifier que la facture appartient au client
        if ($facture->user_id !== Auth::id()) {
            abort(403);
        }
        
        $facture->load('contrat', 'formule.energie');
        
        return view('client.factures.show', compact('facture'));
    }

    /**
     * Marquer une facture comme payée.
     */
    public function marquerPayee(Facture $facture)
    {
        $user = Auth::user();
        
        // Vérifier que la facture est liée à une formule du vendeur
        if ($facture->formule->user_id !== $user->id) {
            abort(403);
        }
        
        $facture->update([
            'statut_paiement' => 'payée',
            'date_paiement' => now(),
        ]);
        
        return redirect()->back()
            ->with('success', 'La facture a été marquée comme payée.');
    }

    /**
     * Generate PDF for invoice.
     */
    public function pdf(Facture $facture)
    {
        $user = Auth::user();
        
        // Vérifier que la facture est liée à une formule du vendeur ou au client
        if ($facture->formule->user_id !== $user->id && $facture->user_id !== $user->id) {
            abort(403);
        }
        
        $facture->load('client', 'contrat', 'formule.energie');
        
        $pdf = PDF::loadView('pdf.facture', compact('facture'));
        
        return $pdf->download('facture-' . $facture->id . '.pdf');
    }

    /**
     * Marquer une facture comme payée par le client.
     */
    public function clientPayer(Facture $facture)
    {
        // Vérifier que la facture appartient au client
        if ($facture->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Vérifier que la facture n'est pas déjà payée
        if ($facture->statut_paiement === 'payée') {
            return redirect()->back()->with('error', 'Cette facture a déjà été payée.');
        }
        
        // Simuler le processus de paiement
        // Dans un cas réel, on redirigerait vers une passerelle de paiement
        
        $facture->update([
            'statut_paiement' => 'payée',
            'date_paiement' => now(),
        ]);
        
        return redirect()->route('client.factures.index')
            ->with('success', 'Votre facture a été payée avec succès.');
    }
}
