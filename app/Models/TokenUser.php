<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class TokenUser extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = ['name', 'email'];

    // Falls du keine Datenbank verwendest, kannst du Dummy-Werte setzen
    public $id = 1;
    public $name = 'Test User';
    public $email = 'test@example.com';

    // Wenn nötig, kannst du noch weitere Methoden hinzufügen,
    // die Laravel erwartet, z.B. getAuthIdentifierName(), getAuthIdentifier()
}
