<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_homepage_returns_ok()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
}
