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

    // convert prices before validation
    protected function prepareForValidation(): void
    {
        $items = collect($this->items ?? [])->map(function (array $item) {
            $item['currency'] ??= 'EUR';

            if (isset($item['unit_price'])) {
                $item['unit_price_cents'] = (int) round($item['unit_price'] * 100);
            }

            return $item;
        })->all();

        $this->merge(['items' => $items]);
        $this->merge([
            'return_number' => $this->return_number ?? $this->generateReturnNumber(),
        ]);
    }

    public function rules(): array
    {
        return [
            'return_number' => ['nullable', 'string', 'max:60'], // если пришло — используем
            'order_reference' => ['sometimes', 'nullable', 'string', 'max:60'], // если пришло — используем
            'reason' => ['sometimes', 'nullable', 'string', 'max:2000'],

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

    private function generateReturnNumber(): string
    {
        return 'RET-' . strtoupper(uniqid());
        // или через БД — RET-000123 и т.д.
    }
}
