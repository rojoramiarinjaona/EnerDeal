<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    use HasFactory;

    protected $fillable = [
        'montant',
        'date_paiement',
        'statut_paiement',
        'contrat_id',
        'user_id',
        'formule_id'
    ];

    public function contrat()
    {
        return $this->belongsTo(Contrat::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function formule()
    {
        return $this->belongsTo(Formule::class);
    }
}
