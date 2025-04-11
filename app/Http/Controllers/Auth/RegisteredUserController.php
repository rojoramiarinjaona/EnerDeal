<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Demande;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'lieu_de_residence' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'in:client,vendeur,admin'],
        ]);

        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'lieu_de_residence' => $request->lieu_de_residence,
        ]);

        // Gérer le rôle selon la sélection
        if ($request->role === 'vendeur') {
            // Pour les vendeurs, créer une demande d'approbation
            $demande = Demande::create([
                'status' => 'en_attente',
                'user_id' => $user->id
            ]);
            
            // Assigner rôle client temporairement pour accès limité
            $user->assignRole('client');
            
            event(new Registered($user));
            Auth::login($user);
            
            // Rediriger vers la page d'accueil avec message d'attente
            return redirect('/')->with('status', 'Votre demande de compte vendeur a été soumise. Veuillez attendre l\'approbation par un administrateur.');
        } else {
            // Pour les clients et admins, assignation directe du rôle
            $role = Role::findByName($request->role);
            $user->assignRole($role);
            
            event(new Registered($user));
            Auth::login($user);
            
            // Rediriger vers le dashboard approprié
            if ($request->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('client.dashboard');
            }
        }
    }
} 