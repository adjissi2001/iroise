<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profil extends Model
{
    protected $table = 'profil';
    protected $primaryKey = 'profil_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'nom',
        'prenom',
        'date_naissance',
        'num_tel',
        'num_fixe',
        'adresse',
        'code_postale',
        'commune',
        'ville',
        'role',
        'est_valide',
        'actif',
    ];


    
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }
}
