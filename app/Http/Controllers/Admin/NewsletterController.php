<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NewsletterController extends Controller
{
    public function index(Request $request)
    {
        $query = NewsletterSubscriber::query();

        if ($request->filled('status')) {
            $query->where('is_active', $request->query('status') === 'active');
        }

        if ($request->filled('q')) {
            $query->where('email', 'like', '%'.$request->query('q').'%');
        }

        $subscribers = $query->orderByDesc('id')->paginate(20)->withQueryString();

        $stats = [
            'total' => NewsletterSubscriber::count(),
            'active' => NewsletterSubscriber::where('is_active', true)->count(),
            'inactive' => NewsletterSubscriber::where('is_active', false)->count(),
        ];

        return view('admin.newsletter.index', compact('subscribers', 'stats'));
    }

    public function toggle(NewsletterSubscriber $subscriber)
    {
        $subscriber->update([
            'is_active' => ! $subscriber->is_active,
            'subscribed_at' => $subscriber->is_active ? ($subscriber->subscribed_at ?? now()) : $subscriber->subscribed_at,
        ]);

        return back()->with('success', $subscriber->is_active ? 'Subscriber activated.' : 'Subscriber unsubscribed.');
    }

    public function destroy(NewsletterSubscriber $subscriber)
    {
        $subscriber->delete();

        return back()->with('success', 'Subscriber deleted.');
    }

    public function export(): StreamedResponse
    {
        $filename = 'newsletter-subscribers-'.now()->format('Y-m-d-His').'.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Email', 'Status', 'Subscribed At', 'Created At']);

            NewsletterSubscriber::orderByDesc('id')->chunk(500, function ($subscribers) use ($handle) {
                foreach ($subscribers as $subscriber) {
                    fputcsv($handle, [
                        $subscriber->email,
                        $subscriber->is_active ? 'Active' : 'Unsubscribed',
                        $subscriber->subscribed_at?->format('Y-m-d H:i:s'),
                        $subscriber->created_at?->format('Y-m-d H:i:s'),
                    ]);
                }
            });

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
