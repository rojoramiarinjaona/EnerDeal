<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'image',
        'lieu_de_residence',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relations
    public function factures()
    {
        return $this->hasMany(Facture::class);
    }

    public function declarationIncidents()
    {
        return $this->hasMany(DeclarationIncident::class);
    }

    public function demandes()
    {
        return $this->hasMany(Demande::class);
    }

    public function contrats()
    {
        return $this->hasMany(Contrat::class);
    }

    public function energies()
    {
        return $this->hasMany(Energie::class);
    }

    public function formules()
    {
        return $this->hasMany(Formule::class);
    }
}
