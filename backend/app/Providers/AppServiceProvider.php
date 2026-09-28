<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function ($notifiable, string $token) {
            return rtrim(config('app.frontend_url'), '/') . '/reset-password'
                . '?token=' . rawurlencode($token)
                . '&email=' . rawurlencode($notifiable->getEmailForPasswordReset());
        });

        VerifyEmail::createUrlUsing(function ($notifiable) {
            $temporarySignedUrl = URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(config('auth.verification.expire', 60)),
                [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ]
            );

            // temporarySignedUrl будет на backend route.
            // Нам нужно взять query-параметры signature/expires и скормить фронту.
            $parts = parse_url($temporarySignedUrl);
            parse_str($parts['query'] ?? '', $query);

            return rtrim(config('app.frontend_url'), '/') . '/app/verify-email'
                . '?id=' . $notifiable->getKey()
                . '&hash=' . sha1($notifiable->getEmailForVerification())
                . '&expires=' . ($query['expires'] ?? '')
                . '&signature=' . ($query['signature'] ?? '');
        });
    }
}
