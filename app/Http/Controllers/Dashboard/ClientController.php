<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Formule;
use App\Models\Contrat;
use App\Models\Facture;
use App\Models\DeclarationIncident;
use App\Models\Categorie;
use App\Models\User;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    /**
     * Affiche le tableau de bord du client.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Récupérer les données pour le tableau de bord
        $contratCount = Contrat::where('user_id', $user->id)->count();
        $factureCount = Facture::whereHas('contrat', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->count();
        $facturesImpayees = Facture::whereHas('contrat', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('statut_paiement', 'non_payee')->count();
        $incidentCount = DeclarationIncident::whereHas('contrat', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->count();
        
        // Récupérer les 5 derniers contrats
        $contrats = Contrat::where('user_id', $user->id)
                            ->with('formule.vendeur', 'formule.energie')
                            ->latest()
                            ->take(5)
                            ->get();
        
        // Récupérer les 5 dernières factures
        $factures = Facture::whereHas('contrat', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with('contrat.formule')
        ->latest()
        ->take(5)
        ->get();
        
        // Récupérer les 5 derniers incidents
        $incidents = DeclarationIncident::whereHas('contrat', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with('contrat.formule')
        ->latest()
        ->take(5)
        ->get();
        
        return view('client.dashboard', compact(
            'contratCount',
            'factureCount', 
            'facturesImpayees',
            'incidentCount',
            'contrats',
            'factures',
            'incidents'
        ));
    }
    
    /**
     * Affiche le panier du client.
     *
     * @return \Illuminate\Http\Response
     */
    public function panier()
    {
        $panier = session()->get('panier', []);
        $formulesIds = array_keys($panier);
        
        $formules = [];
        $total = 0;
        
        if (!empty($formulesIds)) {
            $formules = Formule::whereIn('id', $formulesIds)
                               ->with('energie', 'vendeur')
                               ->get();
            
            foreach ($formules as $formule) {
                $formule->quantite = $panier[$formule->id];
                $total += $formule->prix_kwh * $formule->quantite;
            }
        }
        
        return view('client.panier', compact('formules', 'total'));
    }
    
    /**
     * Ajoute une formule au panier.
     *
     * @param  \App\Models\Formule  $formule
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addToPanier(Formule $formule, Request $request)
    {
        $panier = session()->get('panier', []);
        $quantite = $request->quantite ?? 1;
        
        if (isset($panier[$formule->id])) {
            $panier[$formule->id] += $quantite;
        } else {
            $panier[$formule->id] = $quantite;
        }
        
        session()->put('panier', $panier);
        
        return redirect()->back()->with('success', 'La formule a été ajoutée au panier.');
    }
    
    /**
     * Supprime une formule du panier.
     *
     * @param  \App\Models\Formule  $formule
     * @return \Illuminate\Http\Response
     */
    public function removeFromPanier(Formule $formule)
    {
        $panier = session()->get('panier', []);
        
        if (isset($panier[$formule->id])) {
            unset($panier[$formule->id]);
            session()->put('panier', $panier);
        }
        
        return redirect()->back()->with('success', 'La formule a été retirée du panier.');
    }
    
    /**
     * Affiche le profil du client.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        $client = Auth::user();
        return view('client.profile', compact('client'));
    }
    
    /**
     * Met à jour le profil du client.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'telephone' => 'nullable|string|max:20',
            'lieu_de_residence' => 'nullable|string|max:255',
        ]);

        $client = Auth::user();
        $client->nom = $request->nom;
        $client->prenom = $request->prenom;
        $client->email = $request->email;
        $client->telephone = $request->telephone;
        $client->lieu_de_residence = $request->lieu_de_residence;
        $client->save();

        return redirect()->route('client.profile')->with('success', 'Votre profil a été mis à jour avec succès.');
    }
    
    /**
     * Affiche la page de changement de mot de passe.
     *
     * @return \Illuminate\Http\Response
     */
    public function changePassword()
    {
        return view('client.change-password');
    }
    
    /**
     * Met à jour le mot de passe du client.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $client = Auth::user();

        if (!Hash::check($request->current_password, $client->password)) {
            return back()->with('error', 'Le mot de passe actuel est incorrect.');
        }

        $client->password = Hash::make($request->password);
        $client->save();

        return redirect()->route('client.profile')->with('success', 'Votre mot de passe a été mis à jour avec succès.');
    }
    
    /**
     * Recherche des formules d'énergie.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchFormules(Request $request)
    {
        $query = Formule::with(['energie', 'vendeur'])
                        ->where('est_active', true);
        
        if ($request->has('energie_id') && !empty($request->energie_id)) {
            $query->where('energie_id', $request->energie_id);
        }
        
        if ($request->has('prix_min') && !empty($request->prix_min)) {
            $query->where('prix_kwh', '>=', $request->prix_min);
        }
        
        if ($request->has('prix_max') && !empty($request->prix_max)) {
            $query->where('prix_kwh', '<=', $request->prix_max);
        }
        
        if ($request->has('vendeur_id') && !empty($request->vendeur_id)) {
            $query->where('user_id', $request->vendeur_id);
        }
        
        if ($request->has('mot_cle') && !empty($request->mot_cle)) {
            $motCle = $request->mot_cle;
            $query->where(function($q) use ($motCle) {
                $q->where('nom', 'LIKE', "%{$motCle}%")
                  ->orWhere('description', 'LIKE', "%{$motCle}%");
            });
        }
        
        $formules = $query->paginate(12);
        
        return view('client.formules.search', compact('formules'));
    }
}
