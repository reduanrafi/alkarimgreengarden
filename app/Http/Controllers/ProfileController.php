<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Upload the user's profile photo.
     */
    public function avatar(Request $request): RedirectResponse
    {
        $user = $request->user();

        $request->validate([
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($user->photo) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->photo);
        }

        $path = $request->file('photo')->store('avatars', 'public');
        $user->update(['photo' => $path]);

        return back()->with('status', 'avatar-updated');
    }

    /**
     * Remove the user's profile photo.
     */
    public function removeAvatar(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->photo) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->photo);
            $user->update(['photo' => null]);
        }

        return back()->with('status', 'avatar-removed');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
