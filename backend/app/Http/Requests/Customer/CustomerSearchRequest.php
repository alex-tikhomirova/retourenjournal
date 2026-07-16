<?php
/**
 * Retourenmanagement System
 *
 * @copyright 2026 Alexandra Tikhomirova
 * @license Proprietary
 */

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

/**
 * CustomerSearchRequest
 *
 * @author Alexandra Tikhomirova
 */
class CustomerSearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->current_organization_id;
    }

    public function rules(): array
    {
        return [
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => 'E-Mail',
            'phone' => 'Telefonnummer',
        ];
    }

    public function customer(): array
    {
        return $this->validated();
    }
}
