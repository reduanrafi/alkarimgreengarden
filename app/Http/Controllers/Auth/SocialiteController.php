<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirectToGoogle(string $role): RedirectResponse
    {
        if (! in_array($role, ['customer', 'seller'])) {
            abort(404);
        }

        if (! $this->hasGoogleCredentials()) {
            return back()->withErrors([
                'email' => 'Google sign-in is not configured yet. Please contact the administrator.',
            ]);
        }

        session(['socialite_role' => $role]);

        return Socialite::driver('google')
            ->redirectUrl(route('google.callback'))
            ->redirect();
    }

    public function handleGoogleCallback(Request $request): RedirectResponse
    {
        $role = session('socialite_role', 'customer');
        $errorRoute = $role === 'seller' ? 'seller.login' : 'login';

        // Google returned an error (user denied consent, access blocked, invalid client, ...)
        if ($request->has('error')) {
            return redirect()->route($errorRoute)->withErrors([
                'email' => 'Google sign-in was unsuccessful. Please try again.',
            ]);
        }

        if (! $this->hasGoogleCredentials()) {
            return redirect()->route($errorRoute)->withErrors([
                'email' => 'Google sign-in is not configured yet. Please contact the administrator.',
            ]);
        }

        try {
            $googleUser = Socialite::driver('google')
                ->redirectUrl(route('google.callback'))
                ->user();
        } catch (\Throwable $e) {
            Log::warning('Google sign-in callback failed: ' . $e->getMessage());

            return redirect()->route($errorRoute)->withErrors([
                'email' => 'Google sign-in failed. Please try again.',
            ]);
        }

        $googleId = $googleUser->getId();
        $email = $googleUser->getEmail();

        if (! $email) {
            return redirect()->route($errorRoute)->withErrors([
                'email' => 'We could not retrieve an email address from your Google account.',
            ]);
        }

        $user = User::where('google_id', $googleId)->first();

        if ($user) {
            if ($user->isAdmin()) {
                return redirect()->route('login')->withErrors([
                    'email' => 'Admin login via Google is not allowed.',
                ]);
            }

            if ($user->isBlocked()) {
                return redirect()->route($errorRoute)->withErrors([
                    'email' => 'Your account has been blocked. Please contact support.',
                ]);
            }

            Auth::login($user);
            $request->session()->regenerate();

            return $this->redirectToRoleDashboard($user);
        }

        $existingUser = User::where('email', $email)->first();

        if ($existingUser) {
            if ($existingUser->isAdmin()) {
                return redirect()->route($errorRoute)->withErrors([
                    'email' => 'Admin login via Google is not allowed.',
                ]);
            }

            if ($existingUser->isBlocked()) {
                return redirect()->route($errorRoute)->withErrors([
                    'email' => 'Your account has been blocked. Please contact support.',
                ]);
            }

            $existingUser->update([
                'google_id' => $googleId,
                'avatar' => $googleUser->getAvatar(),
                'email_verified_at' => $existingUser->email_verified_at ?? now(),
            ]);

            Auth::login($existingUser);
            $request->session()->regenerate();

            return $this->redirectToRoleDashboard($existingUser);
        }

        $user = User::create([
            'name' => $googleUser->getName(),
            'email' => $email,
            'google_id' => $googleId,
            'avatar' => $googleUser->getAvatar(),
            'role' => $role,
            'email_verified_at' => now(),
            'password' => bcrypt(Str::password(32)),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        if ($user->isSeller()) {
            return redirect()->intended(route('seller.dashboard'));
        }

        return redirect()->intended(route('dashboard'));
    }

    private function redirectToRoleDashboard(User $user): RedirectResponse
    {
        if ($user->isSeller()) {
            return redirect()->intended(route('seller.dashboard'));
        }

        return redirect()->intended(route('dashboard'));
    }

    /**
     * True when a real Google OAuth client is configured (not missing or a placeholder).
     */
    private function hasGoogleCredentials(): bool
    {
        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');

        return filled($clientId)
            && filled($clientSecret)
            && ! str_starts_with((string) $clientId, 'your-google-')
            && ! str_starts_with((string) $clientSecret, 'your-google-');
    }
}
