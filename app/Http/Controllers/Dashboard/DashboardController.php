<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Rediriger l'utilisateur vers le dashboard approprié en fonction de son rôle.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('vendeur')) {
            return redirect()->route('vendeur.dashboard');
        } elseif ($user->hasRole('client')) {
            return redirect()->route('client.dashboard');
        }
        
        return redirect()->route('home');
    }
}
