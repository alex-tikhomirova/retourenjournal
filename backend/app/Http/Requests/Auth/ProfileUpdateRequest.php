<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

/**
 * Validates editable profile fields. Email is intentionally immutable here.
 */
class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Name',
            'password' => 'Passwort',
        ];
    }
}
