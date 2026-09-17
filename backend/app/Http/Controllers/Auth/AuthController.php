<?php
/**
 * Retourenmanagement System
 *
 * @copyright 2026 Alexandra Tikhomirova
 * @license Proprietary
 */

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ProfileUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

/**
 * AuthController
 *
 * @author Alexandra Tikhomirova
 */
class AuthController extends Controller
{

    /**
     * Handle an incoming registration request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        event(new Registered($user));

        $user->sendEmailVerificationNotification();

        Auth::login($user);
        $request->session()->regenerate();

        return response()->json([
            'user' => new UserResource($user),
        ], 201);
    }

    /**
     * Handle an incoming authentication request.
     * @throws ValidationException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return response()->json([
            'user' => new UserResource($request->user()),
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'user' => new UserResource($request->user()),
        ]);
    }

    /**
     * Update the authenticated user's editable profile fields.
     */
    public function update(ProfileUpdateRequest $request): JsonResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        $user->name = $validated['name'];
        if (!empty($validated['password'])) {
            $user->password = $validated['password'];
        }
        $user->save();

        return response()->json([
            'user' => new UserResource($user),
        ]);
    }

    /**
     * Permanently delete the authenticated user and their owned current organization.
     */
    public function deleteProfile(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'password' => ['required', 'string'],
        ]);

        /** @var User $user */
        $user = $request->user();

        if (!Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'password' => 'Das Konto konnte nicht gelöscht werden. Bitte prüfen Sie Ihr Passwort und versuchen Sie es erneut.',
            ]);
        }

        $organization = $user->currentOrganization;
        $ownsOrganization = $organization && $user->organizations()
            ->where('organizations.id', $organization->id)
            ->wherePivot('is_owner', true)
            ->exists();

        if ($ownsOrganization && $organization->users()->count() > 1) {
            return response()->json([
                'message' => 'Die Organisation hat weitere Mitglieder. Übertragen Sie die Inhaberschaft oder entfernen Sie die Mitglieder, bevor Sie Ihr Konto löschen.',
            ], 409);
        }

        // Logout first: the session guard rotates remember tokens and would otherwise
        // persist the already deleted user model again.
        Auth::guard('web')->logout();

        DB::transaction(function () use ($user, $organization, $ownsOrganization) {
            if ($ownsOrganization) {
                $organization->delete();
            }

            $user->delete();
        });

        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return response()->json(['ok' => true]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['ok' => true]);
    }
}
