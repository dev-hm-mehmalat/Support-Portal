<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/*
|--------------------------------------------------------------------------
| Pest Test Configuration
|--------------------------------------------------------------------------
|
| Hier konfigurieren wir, welche Basisklasse und Traits für die Tests
| im `tests/Feature`-Verzeichnis verwendet werden sollen.
|
*/

uses(TestCase::class, RefreshDatabase::class)
    ->in('Feature');
