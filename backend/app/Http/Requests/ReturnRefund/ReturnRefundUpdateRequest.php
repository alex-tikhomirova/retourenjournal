<?php

namespace App\Http\Requests\ReturnRefund;

use Illuminate\Foundation\Http\FormRequest;

class ReturnRefundUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // позже можно добавить policy
    }

    // convert prices before validation
    protected function prepareForValidation(): void
    {
        $this->merge([
            'amount_cents' =>  $this->get('cost') * 100,
        ]);
    }

    public function rules(): array
    {
        return [
            'reference' => ['nullable', 'string', 'max:255'],
            'status_id' => ['required', 'integer',],
        ];
    }
}
