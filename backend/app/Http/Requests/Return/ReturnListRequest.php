<?php
/**
 * Retourenmanagement System
 *
 * @copyright 2026 Alexandra Tikhomirova
 * @license Proprietary
 */

namespace App\Http\Requests\Return;


use App\Http\Requests\Concerns\HasListQueryParams;
use Illuminate\Foundation\Http\FormRequest;

/**
 * ReturnListRequest
 *
 * Payload shape:
 *   {
 *     filter: {
 *       status_id:       int|string|int[]|string[],
 *       decision_id:     int|string|int[]|string[],
 *       created_at:      [from?, to?],   // date range
 *       updated_at:      [from?, to?],
 *       return_number:   string,
 *       order_reference: string,
 *       customer: { name?, email?, phone? }
 *     },
 *     sort:   "field" | "-field",
 *     page:   { current_page?: int|null, per_page?: int }
 *   }
 *
 * @author Alexandra Tikhomirova
 */
class ReturnListRequest extends FormRequest
{
    use HasListQueryParams;

    public function authorize(): bool
    {
        return (bool) $this->user()?->current_organization_id;
    }

    /**
     * Normalize scalar status_id / decision_id to single-element arrays
     * so that downstream code and `filter.*` rules are uniform.
     */
    protected function prepareForValidation(): void
    {
        $filter = $this->input('filter', []);
        if (!is_array($filter)) {
            return;
        }

        foreach (['status_id', 'decision_id'] as $field) {
            if (isset($filter[$field]) && !is_array($filter[$field])) {
                $filter[$field] = [$filter[$field]];
            }
        }

        $this->merge(['filter' => $filter]);
    }

    public function rules(): array
    {
        return array_merge($this->listBaseRules(), [
            // status / decision — always an array after prepareForValidation
            'filter.status_id'          => ['sometimes', 'array', 'min:0'],
            'filter.status_id.*'        => ['integer', 'min:1'],
            'filter.decision_id'        => ['sometimes', 'array', 'min:1'],
            'filter.decision_id.*'      => ['integer', 'min:1'],

            // date ranges — up to 2 items: [from] or [from, to]
            'filter.created_at'         => ['sometimes', 'array', 'max:2'],
            'filter.created_at.*'       => ['date_format:Y-m-d'],
            'filter.updated_at'         => ['sometimes', 'array', 'max:2'],
            'filter.updated_at.*'       => ['date_format:Y-m-d'],

            // text filters
            'filter.return_number'      => ['sometimes', 'string', 'max:64'],
            'filter.order_reference'    => ['sometimes', 'string', 'max:64'],

            // nested customer
            'filter.customer'           => ['sometimes', 'array'],
            'filter.customer.name'      => ['sometimes', 'string', 'max:200'],
            'filter.customer.email'     => ['sometimes', 'string', 'max:255'],
            'filter.customer.phone'     => ['sometimes', 'string', 'max:64'],

            'filter.q'                  => ['sometimes', 'nullable', 'string', 'max:200'],
        ]);
    }

    public function attributes(): array
    {
        return [
            'filter' => 'Filter',
            'filter.status_id' => 'Retourenstatus',
            'filter.status_id.*' => 'Retourenstatus',
            'filter.decision_id' => 'Entscheidung',
            'filter.decision_id.*' => 'Entscheidung',
            'filter.created_at' => 'Erstellungsdatum',
            'filter.created_at.*' => 'Erstellungsdatum',
            'filter.updated_at' => 'Änderungsdatum',
            'filter.updated_at.*' => 'Änderungsdatum',
            'filter.return_number' => 'Retourennummer',
            'filter.order_reference' => 'Bestellnummer / Referenz',
            'filter.customer' => 'Kunde',
            'filter.customer.name' => 'Name',
            'filter.customer.email' => 'E-Mail',
            'filter.customer.phone' => 'Telefonnummer',
            'filter.q' => 'Suche',
            'sort' => 'Sortierung',
            'pagination' => 'Paginierung',
            'pagination.current_page' => 'Seite',
            'pagination.per_page' => 'Einträge pro Seite',
        ];
    }

    /** Shorthand for the controller. */
    public function filter(): array
    {
        return $this->validated()['filter'] ?? [];
    }

    public function sortMap(): array
    {
        return [
            'created_at'      => 'created_at',
            'updated_at'      => 'updated_at',
            'return_number'   => 'return_number',
            'order_reference' => 'order_reference',
            'status_id'       => 'status_id',
            'customer.name'   => 'customer.name',
        ];
    }

    public function filtersMap(): array
    {
        return [
            'status_id'       => ['type' => 'in',         'column' => 'status_id'],
            'decision_id'     => ['type' => 'in',         'column' => 'decision_id'],

            'created_at'      => ['type' => 'date_range', 'column' => 'created_at'],
            'updated_at'      => ['type' => 'date_range', 'column' => 'updated_at'],

            'return_number'   => ['type' => 'like',       'column' => 'return_number'],
            'order_reference' => ['type' => 'like',       'column' => 'order_reference'],

            'customer' => [
                'type'     => 'relation',
                'relation' => 'customer',
                'filters'  => [
                    'name'  => ['type' => 'like', 'column' => 'name'],
                    'email' => ['type' => 'like', 'column' => 'email'],
                    'phone' => ['type' => 'like', 'column' => 'phone'],
                ],
            ],
        ];
    }

    public function searchMap(): array
    {
        return [
            'return_number',
            'order_reference',
            ['relation' => 'customer', 'columns' => ['name', 'email', 'phone']],
        ];
    }
}
