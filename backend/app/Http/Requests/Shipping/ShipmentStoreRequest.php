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
 * StoreShipmentRequest
 *
 * @author Alexandra Tikhomirova
 */
class ShipmentStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->current_organization_id;
    }

    // convert prices before validation
    protected function prepareForValidation(): void
    {
        if ($this->has('amount')) {
            $amount = $this->input('amount');

            $this->merge([
                'cost_cents' => $amount === null || trim((string) $amount) === ''
                    ? null
                    : (is_numeric($amount) ? (int) round($amount * 100) : $amount),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'return_id' => ['required', 'integer'],
            'direction' => ['required', 'integer', Rule::in([1, 2])],
            'payer' => ['required', 'integer', Rule::in([1,2,3,4,5])],
            'carrier' => ['nullable', 'string', 'max:255'],
            'tracking_number' => ['nullable', 'string', 'max:255'],
            'label_ref' => ['nullable', 'string', 'max:255'],
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
