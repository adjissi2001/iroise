<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beneficiaire extends Model
{
    // Spécifier la table si elle n'est pas la forme plurielle par défaut
    protected $table = 'beneficiaire';

    // Clé primaire personnalisée
    protected $primaryKey = 'id_beneficiaire';

    // Pas de timestamps par défaut dans votre base (adapter si nécessaire)
    public $timestamps = false;

    // Remplissables (adapter selon vos colonnes)
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'num_tel',
        'date_naissance',
    ];
}
