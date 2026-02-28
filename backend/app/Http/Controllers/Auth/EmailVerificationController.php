<?php
/**
 * Retourenmanagement System
 *
 * @copyright 2026 Alexandra Tikhomirova
 * @license Proprietary
 */

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


/**
 * EmailVerificationController
 *
 * @author Alexandra Tikhomirova
 */
class EmailVerificationController extends Controller
{
    /**
     * Resend verification email (auth required)
     */
    public function send(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'ok' => true,
                'status' => 'already-verified',
            ]);
        }

        $user->sendEmailVerificationNotification();

        return response()->json([
            'user' => new UserResource($request->user()),
            'status' => 'verification-link-sent',
        ]);
    }

    public function verify(Request $request, string $id, string $hash): JsonResponse
    {
        $user = $request->user();

        if ((string) $user->getKey() !== (string) $id) {
            return response()->json([
                'message' => 'Invalid verification link.',
            ], 403);
        }

        if (! hash_equals(sha1($user->getEmailForVerification()), (string) $hash)) {
            return response()->json([
                'message' => 'Invalid verification link.',
            ], 403);
        }

        if (! $user->hasVerifiedEmail()) {
            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
            }
        }

        return response()->json([
            'user' => new UserResource($user->fresh()),
        ]);
    }
}
