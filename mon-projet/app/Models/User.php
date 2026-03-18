<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'password',
        'is_admin',
        'actif',
        'temp_password',
        'temp_password_expires_at',
    ];

    public function getPrenomAttribute($value)
    {
        if (!empty($value)) {
            return $value;
        }

        $profil = $this->relationLoaded('profil') ? $this->profil : $this->profil()->first();
        return $profil?->prenom ?? '';
    }

    public function getNomAttribute($value)
    {
        if (!empty($value)) {
            return $value;
        }

        $profil = $this->relationLoaded('profil') ? $this->profil : $this->profil()->first();
        return $profil?->nom ?? '';
    }

    public function getDisplayNameAttribute(): string
    {
        $fullName = trim(($this->prenom ?? '').' '.($this->nom ?? ''));
        if ($fullName !== '') {
            return $fullName;
        }

        return (string) ($this->email ?? '');
    }

    public function getNameAttribute($value)
    {
        if (!empty($value)) {
            return $value;
        }

        $profil = $this->relationLoaded('profil') ? $this->profil : $this->profil()->first();
        if (!$profil) {
            return '';
        }

        return trim(($profil->prenom ?? '').' '.($profil->nom ?? ''));
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relation avec les bénéficiaires
     */
    public function beneficiaires(): HasMany
    {
        return $this->hasMany(Beneficiaire::class);
    }

    public function profil()
    {
        return $this->hasOne(\App\Models\Profil::class, 'user_id', 'id');
    }

    public function voiture(): HasOne
    {
        return $this->hasOne(Voiture::class, 'user_id', 'id');
    }

}
