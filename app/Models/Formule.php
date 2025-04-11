<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formule extends Model
{
    use HasFactory;

    protected $fillable = [
        'ref',
        'intitule',
        'quantite_kwh',
        'duree',
        'taxite',
        'prix_kwh',
        'details_contrat',
        'conditions_resiliation',
        'modalite_livraison',
        'energie_id',
        'user_id'
    ];

    /**
     * Mutateur pour s'assurer que la durée est stockée au bon format
     */
    public function setDureeAttribute($value)
    {
        // Si la valeur contient déjà "mois", on la garde telle quelle
        if (str_contains(strtolower($value), 'mois')) {
            $this->attributes['duree'] = $value;
        } else {
            // Sinon, on extrait le nombre et on ajoute "mois"
            $nombre = intval(preg_replace('/[^0-9]/', '', $value));
            $this->attributes['duree'] = $nombre . ' mois';
        }
    }

    public function energie()
    {
        return $this->belongsTo(Energie::class);
    }

    public function factures()
    {
        return $this->hasMany(Facture::class);
    }

    public function contrats()
    {
        return $this->hasMany(Contrat::class);
    }

    public function demandes()
    {
        return $this->belongsToMany(Demande::class);
    }

    public function vendeur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
