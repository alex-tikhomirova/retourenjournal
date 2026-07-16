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
    }

    public function rules(): array
    {
        return [
            'return_number' => ['required', 'string', 'max:60'],
            'order_reference' => ['sometimes', 'nullable', 'string', 'max:60'],
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
            'items.*.serial' => ['nullable','string','max:255'],
            'items.*.item_name' => ['required','string','max:255'],
            'items.*.quantity' => ['required','integer','min:1','max:100000'],
            'items.*.unit_price_cents' => ['nullable','integer','min:0'],
            'items.*.currency' => ['sometimes','string', Rule::in(['EUR'])],
        ];
    }

    public function attributes(): array
    {
        return [
            'return_number' => 'Retourennummer',
            'order_reference' => 'Bestellnummer / Referenz',
            'reason' => 'Rücksendegrund',
            'customer' => 'Kunde',
            'customer.id' => 'Kunde',
            'customer.name' => 'Name',
            'customer.email' => 'E-Mail',
            'customer.phone' => 'Telefonnummer',
            'customer.address_text' => 'Adresse',
            'items' => 'Artikel',
            'items.*.line_no' => 'Positionsnummer',
            'items.*.sku' => 'SKU',
            'items.*.serial' => 'Seriennummer',
            'items.*.item_name' => 'Artikelname',
            'items.*.quantity' => 'Menge',
            'items.*.unit_price_cents' => 'Stückpreis',
            'items.*.currency' => 'Währung',
        ];
    }

    private function generateReturnNumber(): string
    {
        return 'RET-' . strtoupper(uniqid());
        // or from DB — RET-000123 и etc.
    }
}
