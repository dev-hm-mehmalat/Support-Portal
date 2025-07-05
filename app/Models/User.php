<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; // Trait für Rollen und Berechtigungen
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens; // Wichtig für API-Token

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles; // HasApiTokens hier hinzufügen

    /**
     * Die Attribute, die massenweise befüllt werden dürfen.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Die Attribute, die bei der Serialisierung verborgen werden sollen,
     * z.B. wenn das Modell als JSON ausgegeben wird.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Die Attribute, die als Typ gecastet werden sollen.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
