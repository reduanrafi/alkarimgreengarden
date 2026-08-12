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

        $subscriber = NewsletterSubscriber::where('email', $validated['email'])->first();

        if (! $subscriber) {
            $subscriber = NewsletterSubscriber::create([
                'email' => $validated['email'],
                'is_active' => true,
                'subscribed_at' => now(),
            ]);
        }

        if ($subscriber->is_active) {
            return response()->json([
                'success' => true,
                'message' => $subscriber->wasRecentlyCreated
                    ? 'Thanks for subscribing! Watch your inbox for a confirmation.'
                    : 'You are already subscribed!',
                'unsubscribe_url' => $subscriber->wasRecentlyCreated
                    ? route('newsletter.unsubscribe', $subscriber->unsubscribe_token)
                    : null,
            ]);
        }

        $subscriber->update(['is_active' => true, 'subscribed_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Welcome back! Your subscription has been reactivated.',
        ]);
    }

    public function unsubscribe(Request $request, string $token)
    {
        $subscriber = NewsletterSubscriber::where('unsubscribe_token', $token)->first();

        if (! $subscriber) {
            abort(404);
        }

        if ($request->isMethod('POST')) {
            $subscriber->update(['is_active' => false]);

            return back()->with('status', 'You have been unsubscribed. We are sorry to see you go.');
        }

        return view('newsletter.unsubscribe', ['subscriber' => $subscriber]);
    }
}
