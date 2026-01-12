<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    use HasFactory;
    // Adapter au schéma legacy (`mission` table)
    protected $table = 'mission';
    protected $primaryKey = 'id_mission';
    public $incrementing = true;
    protected $keyType = 'int';

    const CREATED_AT = 'date_creation';
    const UPDATED_AT = null;

    protected $fillable = [
        'id_categorie',
        'nom_lieu',
        'cree_par',
        'date_depart',
        'heure_depart',
        'heure_arrivee',
        'kilometrage',
        'remarques',
        'etat_mission',
    ];

    public function categorie()
    {
        return $this->belongsTo(\App\Models\Categorie::class, 'id_categorie', 'id_categorie');
    }
}
