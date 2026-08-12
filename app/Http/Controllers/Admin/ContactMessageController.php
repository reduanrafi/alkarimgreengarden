<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::query();

        if ($request->filled('status')) {
            $query->where('is_read', $request->query('status') === 'read');
        }

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->query('q').'%')
                    ->orWhere('email', 'like', '%'.$request->query('q').'%')
                    ->orWhere('subject', 'like', '%'.$request->query('q').'%');
            });
        }

        $messages = $query->orderByDesc('id')->paginate(15)->withQueryString();

        $stats = [
            'total' => ContactMessage::count(),
            'unread' => ContactMessage::where('is_read', false)->count(),
        ];

        return view('admin.contact-messages.index', compact('messages', 'stats'));
    }

    public function show(ContactMessage $message)
    {
        if (! $message->is_read) {
            $message->update(['is_read' => true]);
        }

        return view('admin.contact-messages.show', compact('message'));
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();

        return redirect()->route('admin.contact-messages.index')
            ->with('success', 'Contact message deleted.');
    }
}
