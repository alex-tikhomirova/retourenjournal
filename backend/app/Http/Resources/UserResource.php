<?php
/**
 * Retourenmanagement System
 *
 * @copyright 2026 Alexandra Tikhomirova
 * @license Proprietary
 */

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * UserResource
 *
 * @author Alexandra Tikhomirova
 *
 * @mixin User
 *
*/
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'is_verified' => $this->hasVerifiedEmail(),

            // важно для onboarding в твоём multi-tenant
            'current_organization_id' => $this->current_organization_id,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
