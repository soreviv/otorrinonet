<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

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

    public function test_contact_message_invalid_email()
    {
        $response = $this->post(route('contact.store'), [
            'name' => 'John Doe',
            'email' => 'not-an-email',
            'phone' => '1234567890',
            'asunto' => 'Inquiry',
            'message' => 'Hello',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseMissing('contact_messages', [
            'name' => 'John Doe',
        ]);
    }

    public function test_contact_message_missing_fields()
    {
        $response = $this->post(route('contact.store'), []);

        $response->assertSessionHasErrors(['name', 'email', 'phone', 'asunto', 'message']);
    }

    public function test_contact_message_max_length()
    {
        $longString = Str::random(256);

        $response = $this->post(route('contact.store'), [
            'name' => $longString,
            'email' => $longString . '@example.com', // Also invalid email format likely, but checks length first?
            'phone' => $longString,
            'asunto' => $longString,
            'message' => 'Short message',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'phone', 'asunto']);
        $this->assertDatabaseMissing('contact_messages', [
            'message' => 'Short message',
        ]);
    }

    public function test_contact_message_sanitization()
    {
        $response = $this->post(route('contact.store'), [
            'name' => '<b>John Doe</b>',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'asunto' => '<script>alert("xss")</script>',
            'message' => '<p>Hello world</p>',
        ]);

        $response->assertRedirect(route('contact.create'));

        // Assert raw HTML is NOT present (stripped)
        $this->assertDatabaseHas('contact_messages', [
            'name' => 'John Doe',
            'asunto' => 'alert("xss")',
            'message' => 'Hello world',
        ]);

        $this->assertDatabaseMissing('contact_messages', [
            'name' => '<b>John Doe</b>',
        ]);
    }
}
