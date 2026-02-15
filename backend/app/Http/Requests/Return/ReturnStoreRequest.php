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
class ReturnStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->current_organization_id;
    }

    public function rules(): array
    {
        return [
            'return_number' => ['required', 'nullable', 'string', 'max:60'], // если пришло — используем
            'order_reference' => ['sometimes', 'nullable', 'string', 'max:60'], // если пришло — используем
            'internal_note' => ['sometimes', 'nullable', 'string', 'max:2000'],

            'customer' => ['required', 'array'],
            'customer.id' => ['nullable', 'integer'],
            'customer.name' => ['required', 'string', 'max:255'],
            'customer.email' => ['nullable', 'email', 'max:255'],
            'customer.phone' => ['nullable', 'string', 'max:50'],
            'customer.address_text' => ['nullable', 'string', 'max:2000'],


            'items' => ['required','array','min:1','max:500'],
            'items.*.line_no' => ['required','integer','min:1','max:32000'],
            'items.*.sku' => ['nullable','string','max:80'],
            'items.*.item_name' => ['required','string','max:255'],
            'items.*.quantity' => ['required','integer','min:1','max:100000'],
            'items.*.unit_price_cents' => ['nullable','integer','min:0'],
            'items.*.currency' => ['sometimes','string', Rule::in(['EUR'])],
        ];
    }
}
