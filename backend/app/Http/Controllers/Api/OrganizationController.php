<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrganizationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        // Assuming organization_user pivot exists: organization_id, user_id, role
        $orgs = Organization::query()
            ->select('organizations.id', 'organizations.name', 'organizations.created_at')
            ->join('organization_user', 'organization_user.organization_id', '=', 'organizations.id')
            ->where('organization_user.user_id', $user->id)
            ->orderBy('organizations.name')
            ->get();

        return response()->json([
            'current_organization_id' => $user->current_organization_id,
            'organizations' => $orgs,
        ]);
    }

}
