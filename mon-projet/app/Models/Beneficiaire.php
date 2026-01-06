<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'user_id',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
