<?php
/**
 * Retourenmanagement System
 *
 * @copyright 2026 Alexandra Tikhomirova
 * @license Proprietary
 */

namespace App\Http\Resources;


use App\Models\ReturnDecision;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * ReturnDecisionResource
 *
 * @author Alexandra Tikhomirova
 * @mixin ReturnDecision
 */
class ReturnDecisionResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'nextStatus' => $this->nextStatus
        ]);
    }
}
