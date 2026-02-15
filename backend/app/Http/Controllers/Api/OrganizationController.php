<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrganizationStoreRequest;
use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class OrganizationController extends Controller
{
    /**
     * GET /api/organization
     * Current organization of authenticated user.
     */
    public function showCurrent(): JsonResponse
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
            'data' => new OrganizationResource($org),
        ]);
    }


    /**
     * POST /api/organizations
     * Create org + attach user + set current_organization_id
     */
    public function store(OrganizationStoreRequest $request): JsonResponse
    {
        $user = $request->user();

        $org = DB::transaction(function () use ($request, $user) {
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

            return $org;
        });

        return response()->json([
            'data' => new OrganizationResource($org),
        ], 201);
    }


}
