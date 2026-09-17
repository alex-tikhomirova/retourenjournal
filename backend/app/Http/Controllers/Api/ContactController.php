<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Mail\ContactMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Send a message submitted through the public contact form.
     */
    public function send(ContactRequest $request): JsonResponse
    {
        $contact = $request->validated();

        Mail::to(config('mail.contact.address'))->send(
            new ContactMessage($contact, $request->topicLabel())
        );

        return response()->json([
            'message' => 'Ihre Nachricht wurde gesendet.',
        ]);
    }
}
