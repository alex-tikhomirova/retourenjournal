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
        return true; // позже можно добавить policy
    }

    // convert prices before validation
    protected function prepareForValidation(): void
    {
        if ($this->has('cost')) {
            $this->merge([
                'cost_cents' =>  $this->get('cost') * 100,
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'direction' => ['nullable', 'integer', Rule::in([1, 2])],
            'payer' => ['nullable', 'integer', Rule::in([1, 2, 3, 4, 5])],
            'carrier' => ['nullable', 'string', 'max:255'],
            'tracking_number' => ['nullable', 'string', 'max:255'],
            'label_ref' => ['nullable', 'string', 'max:255'],
            'cost_cents' => ['nullable', 'integer', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
        ];
    }
}

