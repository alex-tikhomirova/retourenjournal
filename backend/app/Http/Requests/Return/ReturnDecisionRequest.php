<?php
/**
 * Retourenmanagement System
 *
 * @copyright 2026 Alexandra Tikhomirova
 * @license Proprietary
 */

namespace App\Http\Requests\Return;


use Illuminate\Foundation\Http\FormRequest;

/**
 * ReturnStoreRequest
 *
 * @author Alexandra Tikhomirova
 */
class ReturnDecisionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->current_organization_id;
    }

    public function rules(): array
    {
        return [
            'decision_id' => ['required', 'nullable', 'integer',],
        ];
    }

    public function attributes(): array
    {
        return [
            'decision_id' => 'Entscheidung',
        ];
    }
}
