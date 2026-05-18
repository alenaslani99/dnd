<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MessageController extends Controller
{
    public function index(Request $request): Response
    {
        $query = ContactSubmission::query();

        $search = $request->input('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhere('message', 'like', '%'.$search.'%');
            });
        }

        $status = $request->input('status');
        match ($status) {
            'read' => $query->whereNotNull('read_at'),
            'unread' => $query->whereNull('read_at'),
            default => null,
        };

        $messages = $query
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Admin/Messages/Index', [
            'messages' => $messages->through(fn (ContactSubmission $msg) => [
                'id' => $msg->id,
                'name' => $msg->name,
                'email' => $msg->email,
                'phone' => $msg->phone,
                'message' => $msg->message,
                'is_read' => $msg->read_at !== null,
                'created_at' => $msg->created_at->format('d.m.Y H:i'),
            ]),
            'filters' => [
                'search' => $search,
                'status' => $status,
            ],
        ]);
    }

    public function markAsRead(ContactSubmission $message): RedirectResponse
    {
        if ($message->read_at === null) {
            $message->update(['read_at' => now()]);
        }

        return redirect()->back();
    }
}
