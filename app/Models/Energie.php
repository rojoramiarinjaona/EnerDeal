<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Energie extends Model
{
    use HasFactory;

    protected $fillable = ['titre', 'stock_kwh', 'slug', 'localisation', 'categorie_id', 'user_id'];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function formules()
    {
        return $this->hasMany(Formule::class);
    }

    public function vendeur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
