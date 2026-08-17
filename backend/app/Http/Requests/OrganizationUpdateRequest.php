<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates updates to the user's current organization.
 */
class OrganizationUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        return (bool) $user?->organizations()
            ->where('organizations.id', $user->current_organization_id)
            ->exists();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return ['name' => 'Organisationsname'];
    }
}
