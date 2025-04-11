<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contrat extends Model
{
    use HasFactory;

    protected $fillable = ['date_debut', 'date_fin', 'statut', 'formule_id', 'user_id'];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
    ];

    public function formule()
    {
        return $this->belongsTo(Formule::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function factures()
    {
        return $this->hasMany(Facture::class);
    }

    public function declarationIncidents()
    {
        return $this->hasMany(DeclarationIncident::class);
    }
}
