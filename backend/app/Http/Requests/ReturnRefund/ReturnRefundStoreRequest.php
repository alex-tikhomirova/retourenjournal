<?php

namespace App\Http\Requests\ReturnRefund;

use Illuminate\Foundation\Http\FormRequest;

class ReturnRefundStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // позже можно добавить policy
    }

    // convert prices before validation
    protected function prepareForValidation(): void
    {
        $this->merge([
            'amount_cents' =>  $this->get('amount') * 100,
        ]);
    }

    public function rules(): array
    {
        return [
            'reference' => ['nullable', 'string', 'max:255'],
            'amount_cents' => ['required', 'integer', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
        ];
    }

    public function attributes(): array
    {
        return [
            'reference' => 'Referenz',
            'amount_cents' => 'Erstattungsbetrag',
            'currency' => 'Währung',
        ];
    }
}
