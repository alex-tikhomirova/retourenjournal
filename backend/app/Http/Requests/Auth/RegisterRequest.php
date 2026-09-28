<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

/**
 * Accepts only the two legal documents required during registration.
 */
class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Password::defaults()],
            'legal_acceptances' => ['required', 'array', 'size:2'],
            'legal_acceptances.0' => ['required', 'array:document_key,document_version,document_hash,action'],
            'legal_acceptances.0.document_key' => ['required', 'in:terms'],
            'legal_acceptances.0.document_version' => ['required', 'string', 'max:255'],
            'legal_acceptances.0.document_hash' => ['nullable', 'string', 'max:255'],
            'legal_acceptances.0.action' => ['required', 'in:accepted'],
            'legal_acceptances.1' => ['required', 'array:document_key,document_version,document_hash,action'],
            'legal_acceptances.1.document_key' => ['required', 'in:privacy'],
            'legal_acceptances.1.document_version' => ['required', 'string', 'max:255'],
            'legal_acceptances.1.document_hash' => ['nullable', 'string', 'max:255'],
            'legal_acceptances.1.action' => ['required', 'in:acknowledged'],
        ];
    }
}
