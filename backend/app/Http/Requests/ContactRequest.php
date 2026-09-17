<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContactRequest extends FormRequest
{
    public const TOPICS = [
        'general' => 'Allgemeine Frage',
        'adjustment' => 'Anpassung anfragen',
        'usage' => 'Frage zur Nutzung',
        'technical' => 'Technisches Problem',
        'privacy' => 'Datenschutz',
        'other' => 'Sonstiges',
    ];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'topic' => ['required', Rule::in(array_keys(self::TOPICS))],
            'subject' => ['nullable', 'string', 'max:150'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Name',
            'email' => 'E-Mail-Adresse',
            'topic' => 'Thema',
            'subject' => 'Betreff',
            'message' => 'Nachricht',
        ];
    }

    public function topicLabel(): string
    {
        return self::TOPICS[$this->string('topic')->toString()];
    }
}
