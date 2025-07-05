<?php

namespace Tests\Feature;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TicketApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    #[Test]
    public function user_can_list_tickets(): void
    {
        Sanctum::actingAs($this->user);  // Auth via Sanctum setzen
        $response = $this->getJson('/api/tickets');
        $response->assertStatus(200);
    }

    #[Test]
    public function user_can_create_ticket(): void
    {
        Sanctum::actingAs($this->user);
        $data = [
            'title' => 'Test Ticket',
            'description' => 'Test Beschreibung',
            'category' => 'Software',
            'priority' => 'medium',
        ];
        $response = $this->postJson('/api/tickets', $data);
        $response->assertStatus(201)
                 ->assertJsonFragment(['title' => 'Test Ticket']);
    }


    /** @test */
    public function user_can_show_ticket()
    {
        Sanctum::actingAs($this->user);

        // Erst Ticket erstellen
        $create = $this->postJson('/api/tickets', [
            'title' => 'Show Ticket',
            'description' => 'Beschreibung',
            'category' => 'Hardware',
            'priority' => 'high',
        ]);
        $ticket = $create->json();

        $response = $this->getJson("/api/tickets/{$ticket['id']}");
        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => 'Show Ticket']);
    }

    /** @test */
    public function user_can_update_ticket()
    {
        Sanctum::actingAs($this->user);

        $create = $this->postJson('/api/tickets', [
            'title' => 'Old Title',
            'description' => 'Old Beschreibung',
            'category' => 'Dev',
            'priority' => 'low',
        ]);
        $ticket = $create->json();

        $update = $this->putJson("/api/tickets/{$ticket['id']}", [
            'title' => 'Updated Title',
            'priority' => 'critical',
        ]);

        $update->assertStatus(200)
               ->assertJsonFragment(['title' => 'Updated Title', 'priority' => 'critical']);
    }

    /** @test */
    public function user_can_delete_ticket()
    {
        Sanctum::actingAs($this->user);

        $create = $this->postJson('/api/tickets', [
            'title' => 'Delete Me',
            'description' => 'Wird gelöscht',
            'category' => 'Test',
            'priority' => 'medium',
        ]);
        $ticket = $create->json();

        $del = $this->deleteJson("/api/tickets/{$ticket['id']}");
        $del->assertStatus(204);

        // Prüfe, dass nicht mehr gefunden wird
        $this->getJson("/api/tickets/{$ticket['id']}")->assertStatus(404);
    }
}
