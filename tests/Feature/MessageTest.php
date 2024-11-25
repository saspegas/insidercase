<?php

namespace Tests\Feature;

use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MessageTest extends TestCase
{
    /**
     * Dummy creation test.
     */
    public function test_example(): void
    {
        $response = $this->get('/api/live-test');

        $response->assertStatus(201);
    }

    public function test_message_index(): void
    {
        Message::factory()->count(5)->create();

        $response = $this->get('/api/messages');

        $response->assertStatus(200)->assertJsonCount(5);
    }

    public function test_message_show(): void
    {
        $message = Message::factory()->create();

        $response = $this->get("/api/messages/{$message->id}");

        $response->assertStatus(200)->assertJson($message->toArray());
    }

    public function test_message_store_with_default_values(): void
    {
        $data = [
            'recipient' => '+905555555555',
            'text' => 'Hello, World!',
            'status' => 'new',
        ];

        $response = $this->post('/api/messages', $data);

        $response->assertStatus(201)
            ->assertJson(['message' => 'Message created successfully']);
        
        $this->assertDatabaseHas('messages', $data);
    }

    public function test_message_delete(): void
    {
        $message = Message::factory()->create();

        $response = $this->delete("/api/messages/{$message->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('messages', ['id' => $message->id]);
    }

    public function test_message_update(): void
    {
        $message = Message::factory()->create();

        $data = [
            'recipient' => '+905555555555',
            'text' => 'Hello, World!',
            'status' => 'new',
        ];

        $response = $this->put("/api/messages/{$message->id}", $data);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Message updated successfully']);
        
        $this->assertDatabaseHas('messages', $data);
    }
}
