<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContactMessageTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_message_can_be_stored()
    {
        $response = $this->post(route('contact.store'), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'asunto' => 'Inquiry',
            'message' => 'Hello, world!',
        ]);

        $response->assertRedirect(route('contact.create'));
        $this->assertDatabaseHas('contact_messages', [
            'email' => 'john@example.com',
        ]);
    }
}
