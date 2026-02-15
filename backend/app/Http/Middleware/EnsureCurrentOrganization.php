<?php
/**
 * Retourenmanagement System
 *
 * @copyright 2026 Alexandra Tikhomirova
 * @license Proprietary
 */

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\OrganizationUser;
/**
 * EnsureCurrentOrganization
 *
 * @author Alexandra Tikhomirova
 */
class EnsureCurrentOrganization
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'Nicht authentifiziert.',
            ], 401);
        }

        $orgId = (int) ($user->current_organization_id ?? 0);

        if ($orgId <= 0) {
            // 409 Conflict: user don't have active organization
            return response()->json([
                'message' => 'Keine aktive Organisation ausgewählt.',
                'code' => 'ORG_NOT_SELECTED',
            ], 409);
        }

        $isMember = OrganizationUser::query()
            ->where('organization_id', $orgId)
            ->where('user_id', $user->id)
            ->exists();

        if (!$isMember) {
            return response()->json([
                'message' => 'Kein Zugriff auf die ausgewählte Organisation.',
                'code' => 'ORG_ACCESS_DENIED',
            ], 403);
        }

        // Put org_id to request
        $request->attributes->set('org_id', $orgId);

        return $next($request);
    }
}
