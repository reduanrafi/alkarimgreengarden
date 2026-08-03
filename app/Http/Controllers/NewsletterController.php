<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $exists = NewsletterSubscriber::where('email', $validated['email'])->exists();

        if (! $exists) {
            NewsletterSubscriber::create([
                'email' => $validated['email'],
                'subscribed_at' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => $exists
                ? 'You are already subscribed!'
                : 'Thanks for subscribing! Watch your inbox for a confirmation.',
        ]);
    }
}
