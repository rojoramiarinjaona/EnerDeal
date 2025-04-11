<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeclarationIncident extends Model
{
    use HasFactory;

    protected $fillable = ['titre', 'details', 'niveau', 'statut', 'user_id', 'contrat_id', 'date_resolution'];

    protected $casts = [
        'date_resolution' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function contrat()
    {
        return $this->belongsTo(Contrat::class);
    }
}
