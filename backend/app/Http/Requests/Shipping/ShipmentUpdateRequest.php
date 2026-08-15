<?php
/**
 * Retourenmanagement System
 *
 * @copyright 2026 Alexandra Tikhomirova
 * @license Proprietary
 */

namespace App\Http\Requests\Shipping;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * ShipmentUpdateRequest
 *
 * @author Alexandra Tikhomirova
 */
class ShipmentUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->current_organization_id;
    }

    // convert prices before validation
    protected function prepareForValidation(): void
    {
        if ($this->has('amount')) {
            $this->merge([
                'cost_cents' =>  $this->input('amount') * 100,
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'tracking_number' => ['nullable', 'string', 'max:255'],
            'label_ref' => ['nullable', 'string', 'max:255'],
            'carrier' => ['nullable', 'string', 'max:255'],
            'status_id' => ['required', 'nullable', 'integer',],
            'cost_cents' => ['nullable', 'integer', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
        ];
    }

    public function attributes(): array
    {
        return [
            'direction' => 'Richtung',
            'payer' => 'Zahler',
            'carrier' => 'Versanddienstleister',
            'tracking_number' => 'Trackingnummer',
            'label_ref' => 'Label-Referenz',
            'cost_cents' => 'Kosten',
            'currency' => 'Währung',
        ];
    }

}
