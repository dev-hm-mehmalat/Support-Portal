<?php

namespace Tests\Feature;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role; 

class TicketApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function user_can_list_tickets(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/tickets');
        $response->assertStatus(200)
                 ->assertJsonIsArray(); // Prüft, ob ein Array zurückkommt
    }

    /** @test */
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
                 ->assertJsonFragment(['title' => 'Test Ticket'])
                 ->assertJsonStructure(['id', 'title', 'description', 'category', 'priority', 'status', 'user_id']);
    }

    /** @test */
    public function user_can_show_ticket()
    {
        Sanctum::actingAs($this->user);

        // Ticket erstellen und als JSON zurückholen
        $create = $this->postJson('/api/tickets', [
            'title' => 'Show Ticket',
            'description' => 'Beschreibung',
            'category' => 'Hardware',
            'priority' => 'high',
        ]);
        $create->assertStatus(201);

        $ticket = $create->json();
        $this->assertArrayHasKey('id', $ticket);

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
        $create->assertStatus(201);

        $ticket = $create->json();
        $this->assertArrayHasKey('id', $ticket);

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
    // 1. Rolle 'admin' sicherstellen und zuweisen (Spatie!)
    Role::findOrCreate('admin');
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    Sanctum::actingAs($admin);

    // 2. Ticket erstellen (als Admin)
    $create = $this->postJson('/api/tickets', [
        'title' => 'Delete Me',
        'description' => 'Wird gelöscht',
        'category' => 'Test',
        'priority' => 'medium',
    ]);
    $ticket = $create->json();

    // 3. Ticket löschen (als Admin!)
    $del = $this->deleteJson("/api/tickets/{$ticket['id']}");
    $del->assertStatus(204);

    // 4. Prüfen, dass es gelöscht wurde
    $this->getJson("/api/tickets/{$ticket['id']}")->assertStatus(404);
}


}
