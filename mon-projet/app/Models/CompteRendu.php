<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompteRendu extends Model
{
    protected $table = 'compte_rendu';
    protected $primaryKey = 'id_compte_rendu';

    protected $fillable = [
        'id_mission',
        'benevole_id',
        'kilometrage',
        'heure_depart_reel',
        'heure_arrivee_reel',
        'remarques_problemes',
    ];

    public function mission()
    {
        return $this->belongsTo(Mission::class, 'id_mission', 'id_mission');
    }

    public function benevole()
    {
        return $this->belongsTo(User::class, 'benevole_id');
    }
}
