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
 * CustomerUpdateRequest
 *
 * @author Alexandra Tikhomirova
 */
class CustomerUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->current_organization_id;
    }

    public function rules(): array
    {
        return [
            'customer' => ['required', 'array:id,organization_id,name,email,phone,address_text'],
            'customer.id' => ['exclude'],
            'customer.organization_id' => ['exclude'],
            'customer.name' => ['required', 'string', 'max:255'],
            'customer.email' => ['nullable', 'email', 'max:255'],
            'customer.phone' => ['nullable', 'string', 'max:50'],
            'customer.address_text' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * Return customer attributes without the form wrapper and readonly id.
     *
     * @return array{name: string, email?: string|null, phone?: string|null, address_text?: string|null}
     */
    public function customer(): array
    {
        return $this->validated('customer');
    }

    public function attributes(): array
    {
        return [
            'customer' => 'Kunde',
            'customer.id' => 'Kunde',
            'customer.organization_id' => 'Organisation',
            'customer.name' => 'Name',
            'customer.email' => 'E-Mail',
            'customer.phone' => 'Telefonnummer',
            'customer.address_text' => 'Adresse',
        ];
    }
}
