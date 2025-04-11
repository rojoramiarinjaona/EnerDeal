<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Energie;
use App\Models\Formule;
use App\Models\Contrat;
use App\Models\Facture;
use App\Models\DeclarationIncident;
use App\Models\Demande;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class VendeurController extends Controller
{
    /**
     * Affiche le tableau de bord du vendeur.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Récupérer les données pour le tableau de bord
        $formuleCount = Formule::where('user_id', $user->id)->count();
        $energieCount = Energie::count();
        $contratCount = Contrat::whereHas('formule', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->count();
        $factureCount = Facture::whereHas('contrat.formule', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->count();
        $incidentCount = DeclarationIncident::whereHas('contrat.formule', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->count();
        
        // Récupérer les 5 dernières formules
        $formules = Formule::where('user_id', $user->id)
                            ->with('energie')
                            ->latest()
                            ->take(5)
                            ->get();
        
        // Récupérer les 5 derniers contrats
        $contrats = Contrat::whereHas('formule', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with('formule', 'client')
        ->latest()
        ->take(5)
        ->get();
        
        // Récupérer les 5 dernières factures
        $factures = Facture::whereHas('contrat.formule', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with('contrat.formule', 'contrat.client')
        ->latest()
        ->take(5)
        ->get();
        
        // Récupérer les 5 derniers incidents
        $incidents = DeclarationIncident::whereHas('contrat.formule', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with('contrat.formule', 'contrat.client')
        ->latest()
        ->take(5)
        ->get();
        
        return view('vendeur.dashboard', compact(
            'formuleCount',
            'energieCount',
            'contratCount',
            'factureCount',
            'incidentCount',
            'formules',
            'contrats',
            'factures',
            'incidents'
        ));
    }
    
    /**
     * Affiche le profil du vendeur.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        $user = Auth::user();
        return view('vendeur.profile', compact('user'));
    }
    
    /**
     * Met à jour le profil du vendeur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'nom_entreprise' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'description' => 'nullable|string',
        ]);
        
        $user->update($validated);
        
        return redirect()->route('vendeur.profile')->with('success', 'Profil mis à jour avec succès.');
    }
    
    /**
     * Affiche la page de changement de mot de passe.
     *
     * @return \Illuminate\Http\Response
     */
    public function changePassword()
    {
        return view('vendeur.change-password');
    }
    
    /**
     * Met à jour le mot de passe du vendeur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        $user->password = Hash::make($validated['password']);
        $user->save();
        
        return redirect()->route('vendeur.profile')->with('success', 'Mot de passe mis à jour avec succès.');
    }
    
    /**
     * Affiche les statistiques de vente.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function salesStats(Request $request)
    {
        $user = Auth::user();
        $periode = $request->periode ?? 'mois';
        
        // Déterminer la période de date
        $dateDebut = null;
        switch ($periode) {
            case 'jour':
                $dateDebut = Carbon::now()->startOfDay();
                $groupBy = 'H'; // Grouper par heure
                $dateFormat = 'H:00';
                break;
            case 'semaine':
                $dateDebut = Carbon::now()->startOfWeek();
                $groupBy = 'Y-m-d';
                $dateFormat = 'd/m';
                break;
            case 'mois':
                $dateDebut = Carbon::now()->startOfMonth();
                $groupBy = 'Y-m-d';
                $dateFormat = 'd/m';
                break;
            case 'annee':
                $dateDebut = Carbon::now()->startOfYear();
                $groupBy = 'Y-m';
                $dateFormat = 'm/Y';
                break;
            default:
                $dateDebut = Carbon::now()->subMonths(1);
                $groupBy = 'Y-m-d';
                $dateFormat = 'd/m';
        }
        
        // Statistiques des contrats
        $contratsStats = Contrat::whereHas('formule', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('created_at', '>=', $dateDebut)
            ->select(DB::raw("DATE_FORMAT(created_at, '{$groupBy}') as date"), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Statistiques des factures
        $facturesStats = Facture::whereHas('contrat.formule', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('created_at', '>=', $dateDebut)
            ->select(DB::raw("DATE_FORMAT(created_at, '{$groupBy}') as date"), DB::raw('SUM(montant) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Statistiques par type d'énergie
        $energieStats = Contrat::whereHas('formule', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('created_at', '>=', $dateDebut)
            ->join('formules', 'contrats.formule_id', '=', 'formules.id')
            ->join('energies', 'formules.energie_id', '=', 'energies.id')
            ->select('energies.nom as energie', DB::raw('count(*) as total'))
            ->groupBy('energies.nom')
            ->orderBy('total', 'desc')
            ->get();
        
        // Formules les plus vendues
        $formulesTopVentes = Contrat::whereHas('formule', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('created_at', '>=', $dateDebut)
            ->join('formules', 'contrats.formule_id', '=', 'formules.id')
            ->select('formules.nom', DB::raw('count(*) as total'))
            ->groupBy('formules.nom')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();
        
        return view('vendeur.stats', compact(
            'contratsStats',
            'facturesStats',
            'energieStats',
            'formulesTopVentes',
            'periode'
        ));
    }
    
    /**
     * Affiche les demandes de formules en attente.
     *
     * @return \Illuminate\Http\Response
     */
    public function demandesFormules()
    {
        $user = Auth::user();
        
        $demandes = Formule::where('user_id', $user->id)
                          ->where('est_active', false)
                          ->where('statut_approbation', 'en_attente')
                          ->with('energie')
                          ->latest()
                          ->paginate(10);
        
        return view('vendeur.demandes-formules', compact('demandes'));
    }
}
