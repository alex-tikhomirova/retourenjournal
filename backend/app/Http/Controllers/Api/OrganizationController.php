<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrganizationStoreRequest;
use App\Http\Requests\OrganizationUpdateRequest;
use App\Http\Resources\OrganizationResource;
use App\Models\LegalAcceptance;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrganizationController extends Controller
{
    /**
     * GET /api/organization
     * Current organization of authenticated user.
     */
    public function showCurrent(Request $request): JsonResponse
    {
        $user = request()->user();

        if (!$user->current_organization_id) {
            return response()->json(['data' => null], 200);
        }

        // проверяем что org реально "его"
        $org = $user->organizations()
            ->where('organizations.id', $user->current_organization_id)
            ->first();

        if (!$org) {
            // можно 200 null, чтобы фронт не падал
            return response()->json(['data' => null], 200);
        }

        return response()->json([
            'data' => $this->organizationData($org, $request),
        ]);
    }


    /**
     * POST /api/organizations
     * Create org + attach user + set current_organization_id
     */
    public function store(OrganizationStoreRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        if ($user->organizations()->wherePivot('is_owner', true)->exists()) {
            abort(409, 'Sie haben bereits eine Organisation erstellt.');
        }

        $acceptance = $request->validated('legal_acceptances')[0];

        $org = DB::transaction(function () use ($request, $user, $acceptance) {
            $org = new Organization([
                'name' => $request->string('name')->toString(),
            ]);
            $org->save();

            // membership в pivot
            $org->users()->attach($user->id, [
                'is_owner' => true,
            ]);

            // current org
            $user->forceFill(['current_organization_id' => $org->id])->save();

            LegalAcceptance::create([
                'organization_id' => $org->id,
                'user_id' => $user->id,
                'actor_type' => 'user',
                'document_key' => 'avv',
                'document_version' => $acceptance['document_version'],
                'document_hash' => $acceptance['document_hash'] ?? null,
                'action' => 'contract_concluded',
                'context' => 'organization_creation',
                'accepted_at' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return $org;
        });

        return response()->json([
            'data' => $this->organizationData($org, $request),
        ], 201);
    }

    /**
     * Update the authenticated user's current organization.
     */
    public function update(OrganizationUpdateRequest $request): JsonResponse
    {
        $user = $request->user();
        $org = $user->organizations()
            ->where('organizations.id', $user->current_organization_id)
            ->firstOrFail();

        $org->update($request->validated());

        return response()->json([
            'data' => $this->organizationData($org, $request),
        ]);
    }

    /**
     * Include only the latest concluded AVV and its signer in organization responses.
     *
     * @return array<string, mixed>
     */
    private function organizationData(Organization $org, Request $request): array
    {
        $acceptance = LegalAcceptance::query()
            ->with('user:id,name,email')
            ->where('organization_id', $org->id)
            ->where('document_key', 'avv')
            ->where('action', 'contract_concluded')
            ->where('context', 'organization_creation')
            ->latest('id')
            ->first();

        return [
            ...(new OrganizationResource($org))->resolve($request),
            'avv_acceptance' => $acceptance ? [
                'accepted_at' => $acceptance->accepted_at->toISOString(),
                'document_version' => $acceptance->document_version,
                'user' => $acceptance->user ? [
                    'name' => $acceptance->user->name,
                    'email' => $acceptance->user->email,
                ] : null,
            ] : null,
        ];
    }
}
