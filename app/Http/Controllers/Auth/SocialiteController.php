<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirectToGoogle(string $role): RedirectResponse
    {
        if (! in_array($role, ['customer', 'seller'])) {
            abort(404);
        }

        session(['socialite_role' => $role]);

        return Socialite::driver('google')
            ->redirectUrl(route('google.callback'))
            ->redirect();
    }

    public function handleGoogleCallback(Request $request): RedirectResponse
    {
        $role = session('socialite_role', 'customer');

        try {
            $googleUser = Socialite::driver('google')
                ->redirectUrl(route('google.callback'))
                ->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'email' => 'Google sign-in failed. Please try again.',
            ]);
        }

        $user = User::where('google_id', $googleUser->getId())->first();

        if ($user) {
            if ($user->isAdmin()) {
                return redirect()->route('login')->withErrors([
                    'email' => 'Admin login via Google is not allowed.',
                ]);
            }

            if ($user->isBlocked()) {
                return redirect()->route('login')->withErrors([
                    'email' => 'Your account has been blocked. Please contact support.',
                ]);
            }

            Auth::login($user);
            $request->session()->regenerate();

            return $this->redirectToRoleDashboard($user);
        }

        $existingUser = User::where('email', $googleUser->getEmail())->first();

        if ($existingUser) {
            if ($existingUser->isAdmin()) {
                return redirect()->route('login')->withErrors([
                    'email' => 'Admin login via Google is not allowed.',
                ]);
            }

            if ($existingUser->isBlocked()) {
                return redirect()->route('login')->withErrors([
                    'email' => 'Your account has been blocked. Please contact support.',
                ]);
            }

            $existingUser->update([
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'email_verified_at' => $existingUser->email_verified_at ?? now(),
            ]);

            Auth::login($existingUser);
            $request->session()->regenerate();

            return $this->redirectToRoleDashboard($existingUser);
        }

        $user = User::create([
            'name' => $googleUser->getName(),
            'email' => $googleUser->getEmail(),
            'google_id' => $googleUser->getId(),
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
}
