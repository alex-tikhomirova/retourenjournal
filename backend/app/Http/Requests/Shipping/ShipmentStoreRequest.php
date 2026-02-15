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
        return true; // позже можно добавить policy
    }

    public function rules(): array
    {
        return [
            'direction' => ['required', 'integer', Rule::in([1, 2])],
            'payer' => ['required', 'integer', Rule::in([1,2,3,4,5])],
            'carrier' => ['nullable', 'string', 'max:255'],
            'tracking_number' => ['nullable', 'string', 'max:255'],
            'label_ref' => ['nullable', 'string', 'max:255'],
            'cost_cents' => ['nullable', 'integer', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
        ];
    }
}
