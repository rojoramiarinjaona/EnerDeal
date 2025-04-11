<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Demande;
use App\Models\Contrat;
use App\Models\Facture;
use App\Models\Energie;
use App\Models\Formule;
use App\Models\DeclarationIncident;
use App\Models\Categorie;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Affiche le dashboard administrateur.
     */
    public function dashboard()
    {
        $stats = [
            'nbVendeurs' => User::role('vendeur')->count(),
            'nbClients' => User::role('client')->count(),
            'nbContrats' => Contrat::count(),
            'nbFactures' => Facture::count(),
            'montantFactures' => Facture::sum('montant'),
            'nbEnergies' => Energie::count(),
            'nbFormules' => Formule::count(),
        ];
        
        return view('admin.dashboard', compact('stats'));
    }
    
    /**
     * Affiche la liste des vendeurs.
     */
    public function vendeurs()
    {
        $vendeurs = User::role('vendeur')->paginate(10);
        return view('admin.vendeurs.index', compact('vendeurs'));
    }
    
    /**
     * Affiche les détails d'un vendeur.
     */
    public function showVendeur(User $user)
    {
        if (!$user->hasRole('vendeur')) {
            abort(404);
        }
        
        $energies = Energie::where('user_id', $user->id)->get();
        $formules = Formule::where('user_id', $user->id)->get();
        $contrats = Contrat::whereIn('formule_id', $formules->pluck('id'))->get();
        
        return view('admin.vendeurs.show', compact('user', 'energies', 'formules', 'contrats'));
    }
    
    /**
     * Approuve un utilisateur comme vendeur.
     */
    public function approveVendeur(User $user)
    {
        if (!$user->hasRole('vendeur')) {
            $vendeurRole = Role::findByName('vendeur');
            $user->roles()->syncWithoutDetaching([$vendeurRole->id]);
        }
        
        return redirect()->back()->with('success', 'Utilisateur approuvé comme vendeur.');
    }
    
    /**
     * Retire le statut de vendeur à un utilisateur.
     */
    public function rejectVendeur(User $user)
    {
        if ($user->hasRole('vendeur')) {
            $user->removeRole('vendeur');
        }
        
        return redirect()->back()->with('success', 'Statut vendeur retiré avec succès.');
    }
    
    /**
     * Affiche la liste de tous les utilisateurs.
     */
    public function users()
    {
        $users = User::with('roles')->get();
        return view('admin.users.index', compact('users'));
    }
    
    /**
     * Affiche les détails d'un utilisateur.
     */
    public function showUser(User $user)
    {
        $roles = Role::all();
        return view('admin.users.show', compact('user', 'roles'));
    }
    
    /**
     * Met à jour les informations d'un utilisateur.
     */
    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'lieu_de_residence' => 'required|string|max:255',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);
        
        $user->update([
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'],
            'lieu_de_residence' => $validated['lieu_de_residence'],
        ]);
        
        // Mise à jour des rôles
        $roles = Role::whereIn('id', $validated['roles'])->get();
        $user->syncRoles($roles);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur mis à jour avec succès');
    }
    
    /**
     * Réinitialise le mot de passe d'un utilisateur.
     */
    public function resetPassword(User $user)
    {
        // Mot de passe par défaut: nom.prenom
        $defaultPassword = strtolower($user->nom . '.' . $user->prenom);
        
        $user->password = Hash::make($defaultPassword);
        $user->save();
        
        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Mot de passe réinitialisé avec succès. Nouveau mot de passe: ' . $defaultPassword);
    }
    
    /**
     * Affiche les statistiques avancées du système.
     */
    public function advancedStats(Request $request)
    {
        // Période par défaut: ce mois-ci
        $start = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $end = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();
        
        // Statistiques de ventes par jour
        $salesByDay = Facture::whereBetween('created_at', [$start, $end])
                        ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(montant) as total'), DB::raw('COUNT(*) as count'))
                        ->groupBy('date')
                        ->orderBy('date')
                        ->get();
        
        // Statistiques par catégorie d'énergie
        $salesByCategory = DB::table('factures')
                            ->join('formules', 'factures.formule_id', '=', 'formules.id')
                            ->join('energies', 'formules.energie_id', '=', 'energies.id')
                            ->join('categories', 'energies.categorie_id', '=', 'categories.id')
                            ->select('categories.nom', DB::raw('SUM(factures.montant) as total'), DB::raw('COUNT(*) as count'))
                            ->whereBetween('factures.created_at', [$start, $end])
                            ->groupBy('categories.nom')
                            ->get();
        
        // Top 5 des vendeurs
        $topVendeurs = DB::table('users')
                        ->join('formules', 'users.id', '=', 'formules.user_id')
                        ->join('factures', 'formules.id', '=', 'factures.formule_id')
                        ->select('users.id', 'users.nom', 'users.prenom', DB::raw('SUM(factures.montant) as total'), DB::raw('COUNT(*) as count'))
                        ->whereBetween('factures.created_at', [$start, $end])
                        ->groupBy('users.id', 'users.nom', 'users.prenom')
                        ->orderBy('total', 'desc')
                        ->limit(5)
                        ->get();
        
        // Évolution des inscriptions
        $registrations = User::whereBetween('created_at', [$start, $end])
                        ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
                        ->groupBy('date')
                        ->orderBy('date')
                        ->get();
        
        // Évolution des incidents
        $incidents = DeclarationIncident::whereBetween('created_at', [$start, $end])
                        ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
                        ->groupBy('date')
                        ->orderBy('date')
                        ->get();
        
        return view('admin.advanced-stats', compact(
            'salesByDay',
            'salesByCategory',
            'topVendeurs',
            'registrations',
            'incidents',
            'start',
            'end'
        ));
    }
    
    /**
     * Affiche les informations système.
     */
    public function systemInfo()
    {
        $dbSize = DB::select('SELECT pg_size_pretty(pg_database_size(current_database())) as size')[0]->size;
        
        $latestUsers = User::latest()->take(5)->get();
        $latestContracts = Contrat::with('client', 'formule')->latest()->take(5)->get();
        $latestIncidents = DeclarationIncident::with('client')->latest()->take(5)->get();
        
        $systemInfo = [
            'php_version' => phpversion(),
            'database_size' => $dbSize,
            'laravel_version' => app()->version(),
            'memory_usage' => memory_get_usage(true),
            'storage_usage' => disk_free_space('/'),
            'total_storage' => disk_total_space('/'),
        ];
        
        return view('admin.system-info', compact(
            'systemInfo',
            'latestUsers',
            'latestContracts',
            'latestIncidents'
        ));
    }
    
    /**
     * Affiche et gère les catégories d'énergie.
     */
    public function categories()
    {
        $categories = Categorie::withCount('energies')->get();
        return view('admin.categories.index', compact('categories'));
    }
    
    /**
     * Crée une nouvelle catégorie.
     */
    public function storeCategorie(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:categories',
            'slug' => 'required|string|max:255|unique:categories',
        ]);
        
        Categorie::create($validated);
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie créée avec succès');
    }
    
    /**
     * Met à jour une catégorie.
     */
    public function updateCategorie(Request $request, Categorie $categorie)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:categories,nom,'.$categorie->id,
            'slug' => 'required|string|max:255|unique:categories,slug,'.$categorie->id,
        ]);
        
        $categorie->update($validated);
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie mise à jour avec succès');
    }
    
    /**
     * Supprime une catégorie.
     */
    public function deleteCategorie(Categorie $categorie)
    {
        // Vérifier s'il existe des énergies associées
        if ($categorie->energies()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Impossible de supprimer cette catégorie car elle contient des énergies');
        }
        
        $categorie->delete();
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie supprimée avec succès');
    }
    
    /**
     * Affiche le profil de l'administrateur
     */
    public function profile()
    {
        $user = auth()->user();
        return view('admin.profile', compact('user'));
    }
    
    /**
     * Met à jour le profil de l'administrateur
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'lieu_de_residence' => 'required|string|max:255',
        ]);
        
        $user->update($validated);
        
        return redirect()->route('admin.profile')
            ->with('success', 'Votre profil a été mis à jour avec succès.');
    }
    
    /**
     * Affiche le formulaire de changement de mot de passe
     */
    public function changePassword()
    {
        return view('admin.change-password');
    }
    
    /**
     * Met à jour le mot de passe de l'administrateur
     */
    public function updatePassword(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);
        
        return redirect()->route('admin.change-password')
            ->with('success', 'Votre mot de passe a été mis à jour avec succès.');
    }
}
