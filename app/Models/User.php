<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

/**
 * User Model mit Rollen, Sanctum-API, Factory und Notification
 *
 * @mixin \Spatie\Permission\Traits\HasRoles
 * // ^-- DIESER HINWEIS LÖST DAS PROBLEM FÜR DEINEN EDITOR
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

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
