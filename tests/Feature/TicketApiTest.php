<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TicketApiTest extends TestCase
{
    use RefreshDatabase; // Datenbank wird für jeden Test zurückgesetzt

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        // Erstelle einen User für Authentifizierung
        $this->user = User::factory()->create();
    }

    /** @test */
    public function user_can_list_tickets()
    {
        $response = $this->actingAs($this->user, 'api')->getJson('/api/tickets');

        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_create_ticket()
    {
        $data = [
            'title' => 'Test Ticket',
            'description' => 'Test Beschreibung',
            'category' => 'Software',
            'priority' => 'medium',
            'reported_at' => now()->toDateTimeString(),
        ];

        $response = $this->actingAs($this->user, 'api')->postJson('/api/tickets', $data);

        $response->assertStatus(201)
                 ->assertJsonFragment(['title' => 'Test Ticket']);
    }

    // Weitere Tests für show, update, delete ...
}
