<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Voiture extends Model
{
    protected $table = 'voiture';
    protected $primaryKey = 'id_voiture';
    public $timestamps = false;

    protected $fillable = [
        'num_immatriculation',
        'puissance_voiture',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
