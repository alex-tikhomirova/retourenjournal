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
 * @author Alexandra Tikhomirova
 */
class ReturnListRequest extends FormRequest
{
    use HasListQueryParams;
    public function authorize(): bool
    {
        return (bool) $this->user()?->current_organization_id;
    }

    public function rules(): array
    {
        return array_merge($this->listBaseRules(), [
            'status_id'   => ['sometimes','integer','min:1'],
            'decision_id' => ['sometimes','integer','min:1'],
            'created_from'=> ['sometimes','date_format:Y-m-d'],
            'created_to'  => ['sometimes','date_format:Y-m-d','after_or_equal:created_from'],

            // optional nested: customer[name]=...&customer[email]=...
            'customer'         => ['sometimes','array'],
            'customer.name'    => ['sometimes','string','max:200'],
            'customer.email'   => ['sometimes','string','max:255'],
            'customer.phone'   => ['sometimes','string','max:64'],
        ]);
    }


    public function sortMap(): array
    {
        return [
            'created_at'      => 'created_at',
            'updated_at'      => 'updated_at',
            'return_number'   => 'return_number',
            'order_reference' => 'order_reference',
            'status_id'       => 'status_id',
        ];
    }

    public function filtersMap(): array
    {
        return [
            'status_id'   => ['type' => 'eq', 'column' => 'status_id'],
            'decision_id' => ['type' => 'eq', 'column' => 'decision_id'],

            'created_from' => ['type' => 'date_from', 'column' => 'created_at'],
            'created_to'   => ['type' => 'date_to',   'column' => 'created_at'],

            'customer' => [
                'type' => 'relation',
                'relation' => 'customer',
                'filters' => [
                    'name'  => ['type'=>'like', 'column'=>'name'],
                    'email' => ['type'=>'like', 'column'=>'email'],
                    'phone' => ['type'=>'like', 'column'=>'phone'],
                ],
            ],
        ];
    }

    public function searchMap(): array
    {
        return [
            'return_number',
            'order_reference',
            ['relation' => 'customer', 'columns' => ['name','email','phone']],
        ];
    }
}
