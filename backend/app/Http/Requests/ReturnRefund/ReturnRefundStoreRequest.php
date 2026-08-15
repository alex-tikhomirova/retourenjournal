<?php

namespace App\Http\Requests\ReturnRefund;

use Illuminate\Foundation\Http\FormRequest;

class ReturnRefundStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->current_organization_id;
    }

    // convert prices before validation
    protected function prepareForValidation(): void
    {
        $this->merge([
            'amount_cents' =>  $this->input('amount') * 100,
        ]);
    }

    public function rules(): array
    {
        return [
            'return_id' => ['required', 'integer'],
            'reference' => ['nullable', 'string', 'max:255'],
            'status_id' => ['sometimes', 'nullable', 'integer',],
            'amount_cents' => ['required', 'integer', 'min:1'],
            'currency' => ['nullable', 'string', 'size:3'],
            'processed_at' => ['nullable', 'date'],
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
