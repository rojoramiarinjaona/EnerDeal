<?php

namespace App\Http\Controllers;

use App\Models\DeclarationIncident;
use App\Models\Contrat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeclarationIncidentController extends Controller
{
    /**
     * Affiche la liste des incidents pour les vendeurs.
     */
    public function index()
    {
        $user = Auth::user();
        $formules = $user->formules()->pluck('id');
        $contrats = Contrat::whereIn('formule_id', $formules)->pluck('id');
        
        $incidents = DeclarationIncident::whereIn('contrat_id', $contrats)
                    ->with('client', 'contrat.formule')
                    ->latest()
                    ->get();
        
        return view('vendeur.incidents.index', compact('incidents'));
    }

    /**
     * Affiche les détails d'un incident pour le vendeur.
     */
    public function show(DeclarationIncident $incident)
    {
        $user = Auth::user();
        $contrat = $incident->contrat;
        
        // Vérifier que l'incident est lié à un contrat d'une formule du vendeur
        if (!$contrat || $contrat->formule->user_id !== $user->id) {
            abort(403);
        }
        
        $incident->load('client', 'contrat.formule');
        
        return view('vendeur.incidents.show', compact('incident'));
    }

    /**
     * Affiche le formulaire d'édition d'un incident pour le vendeur.
     */
    public function edit(DeclarationIncident $incident)
    {
        $user = Auth::user();
        $contrat = $incident->contrat;
        
        // Vérifier que l'incident est lié à un contrat d'une formule du vendeur
        if (!$contrat || $contrat->formule->user_id !== $user->id) {
            abort(403);
        }
        
        $incident->load('client', 'contrat.formule');
        
        return view('vendeur.incidents.edit', compact('incident'));
    }

    /**
     * Met à jour le statut d'un incident (pour les vendeurs).
     */
    public function update(Request $request, DeclarationIncident $incident)
    {
        $user = Auth::user();
        $contrat = $incident->contrat;
        
        // Vérifier que l'incident est lié à un contrat d'une formule du vendeur
        if (!$contrat || $contrat->formule->user_id !== $user->id) {
            abort(403);
        }
        
        $validatedData = $request->validate([
            'statut' => 'required|in:nouveau,en cours,résolu',
            'titre' => 'sometimes|required|string|max:255',
            'details' => 'sometimes|required|string',
            'niveau' => 'sometimes|required|integer|between:1,5',
            'resolution' => 'sometimes|nullable|string',
        ]);
        
        // Si l'incident est résolu, enregistrer la date de résolution
        if ($request->statut === 'résolu' && $incident->statut !== 'résolu') {
            $validatedData['date_resolution'] = now();
        }
        
        $incident->update($validatedData);
        
        return redirect()->route('vendeur.incidents.show', $incident)
            ->with('success', 'Incident mis à jour avec succès.');
    }

    /**
     * Affiche la liste des incidents pour le client.
     */
    public function clientIndex()
    {
        $incidents = DeclarationIncident::where('user_id', Auth::id())
                    ->with('contrat.formule')
                    ->latest()
                    ->get();
        
        return view('client.incidents.index', compact('incidents'));
    }

    /**
     * Affiche le formulaire de création d'un incident (pour le client).
     */
    public function clientCreate()
    {
        $contrats = Contrat::where('user_id', Auth::id())
                  ->where('statut', 'actif')
                  ->with('formule')
                  ->get();
        
        return view('client.incidents.create', compact('contrats'));
    }

    /**
     * Enregistre un nouvel incident déclaré par le client.
     */
    public function clientStore(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'details' => 'required|string',
            'niveau' => 'required|integer|between:1,5',
            'contrat_id' => 'required|exists:contrats,id',
        ]);
        
        // Vérifier que le contrat appartient au client
        $contrat = Contrat::findOrFail($request->contrat_id);
        if ($contrat->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Ce contrat ne vous appartient pas.');
        }
        
        DeclarationIncident::create([
            'titre' => $request->titre,
            'details' => $request->details,
            'niveau' => $request->niveau,
            'statut' => 'nouveau',
            'user_id' => Auth::id(),
            'contrat_id' => $request->contrat_id,
        ]);
        
        return redirect()->route('client.incidents.index')
            ->with('success', 'Incident déclaré avec succès. Un vendeur prendra contact avec vous.');
    }

    /**
     * Affiche les détails d'un incident pour le client.
     */
    public function clientShow(DeclarationIncident $incident)
    {
        // Vérifier que l'incident appartient au client
        if ($incident->user_id !== Auth::id()) {
            abort(403);
        }
        
        $incident->load('contrat.formule');
        
        return view('client.incidents.show', compact('incident'));
    }
}
