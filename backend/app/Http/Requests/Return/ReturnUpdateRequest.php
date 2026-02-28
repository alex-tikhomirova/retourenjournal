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
            'return_number' => ['required', 'nullable', 'string', 'max:60'],
            'order_reference' => ['sometimes', 'nullable', 'string', 'max:60'],
            'status_id' => ['required', 'nullable', 'integer',],
            'decision_id' => ['required', 'nullable', 'integer',],
            'reason' => ['sometimes', 'nullable', 'string', 'max:2000'],

        ];
    }
}
