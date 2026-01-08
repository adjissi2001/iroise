<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Utilisateur extends Authenticatable
{
    use Notifiable;

    protected $table = 'utilisateur';

    protected $primaryKey = 'email';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false; // ta table n'a pas created_at/updated_at

    protected $fillable = [
        'email',
        'mot_de_passe',
        'role',
        'actif',
        'remember_token',
    ];

    protected $hidden = [
        'mot_de_passe',
        'remember_token',
    ];

    // IMPORTANT : dire à Laravel que le "password" = mot_de_passe
    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }
}
