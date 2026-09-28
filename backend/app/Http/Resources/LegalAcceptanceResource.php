<?php

namespace App\Http\Resources;

use App\Models\LegalAcceptance;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin LegalAcceptance */
class LegalAcceptanceResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'organization_id' => $this->organization_id,
            'user_id' => $this->user_id,
            'return_id' => $this->return_id,
            'actor_type' => $this->actor_type,
            'document_key' => $this->document_key,
            'document_version' => $this->document_version,
            'document_hash' => $this->document_hash,
            'action' => $this->action,
            'context' => $this->context,
            'accepted_at' => $this->accepted_at?->toISOString(),
            'ip_address' => $this->ip_address,
            'user_agent' => $this->user_agent,
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
