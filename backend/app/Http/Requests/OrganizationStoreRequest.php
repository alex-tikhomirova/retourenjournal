<?php
/**
 * Retourenmanagement System
 *
 * @copyright 2026 Alexandra Tikhomirova
 * @license Proprietary
 */

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

/**
 * OrganizationStoreRequest
 *
 * @author Alexandra Tikhomirova
 */
class OrganizationStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'legal_acceptances' => ['required', 'array', 'size:1'],
            'legal_acceptances.0' => ['required', 'array:document_key,document_version,document_hash,action'],
            'legal_acceptances.0.document_key' => ['required', 'in:avv'],
            'legal_acceptances.0.document_version' => ['required', 'string', 'max:255'],
            'legal_acceptances.0.document_hash' => ['nullable', 'string', 'max:255'],
            'legal_acceptances.0.action' => ['required', 'in:contract_concluded'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Organisationsname',
        ];
    }
}
