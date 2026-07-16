<?php
/**
 * Retourenmanagement System
 *
 * @copyright 2026 Alexandra Tikhomirova
 * @license Proprietary
 */

namespace App\Http\Requests\Return;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * ReturnStoreRequest
 *
 * @author Alexandra Tikhomirova
 */
class ReturnUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->current_organization_id;
    }

    public function rules(): array
    {
        return [
            'return_number' => ['required', 'string', 'max:60'],
            'order_reference' => ['sometimes', 'nullable', 'string', 'max:60'],
            'status_id' => ['required', 'nullable', 'integer',],
            'decision_id' => ['sometimes', 'nullable', 'integer',],
            'reason' => ['sometimes', 'nullable', 'string', 'max:2000'],

        ];
    }

    public function attributes(): array
    {
        return [
            'return_number' => 'Retourennummer',
            'order_reference' => 'Bestellnummer / Referenz',
            'status_id' => 'Retourenstatus',
            'decision_id' => 'Entscheidung',
            'reason' => 'Rücksendegrund',
        ];
    }
}
