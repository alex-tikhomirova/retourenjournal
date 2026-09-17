<?php

namespace Tests\Feature;

use App\Mail\ContactMessage;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactTest extends TestCase
{
    public function test_contact_message_can_be_sent(): void
    {
        Mail::fake();
        config(['mail.contact.address' => 'kontakt@example.com']);

        $response = $this->postJson('/api/contact', [
            'name' => 'Anna Beispiel',
            'email' => 'anna@example.com',
            'topic' => 'adjustment',
            'subject' => 'Eigener Prozess',
            'message' => 'Ich möchte eine Anpassung des Retourenprozesses besprechen.',
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'message' => 'Ihre Nachricht wurde gesendet.',
            ]);

        Mail::assertSent(ContactMessage::class, function (ContactMessage $mail) {
            return $mail->hasTo('kontakt@example.com')
                && $mail->contact['email'] === 'anna@example.com'
                && $mail->topicLabel === 'Anpassung anfragen';
        });
    }

    public function test_contact_form_is_validated(): void
    {
        Mail::fake();

        $response = $this->postJson('/api/contact', [
            'name' => '',
            'email' => 'keine-email',
            'topic' => 'unknown',
            'message' => 'Kurz',
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'name',
                'email',
                'topic',
                'message',
            ]);

        Mail::assertNothingSent();
    }
}
