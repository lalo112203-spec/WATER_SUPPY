<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $posts = \App\Models\Post::with('admin')->orderBy('created_at', 'desc')->get();

        if ($user->role === 'admin') {
            $users = \App\Models\User::where('role', 'consumer')
                ->withCount(['sentMessages as unread_count' => function ($query) {
                    $query->where('receiver_id', auth()->id())->whereNull('read_at');
                }])
                ->orderByDesc('unread_count')
                ->get();
            $messages = \App\Models\Message::with(['sender', 'receiver'])
                ->orderBy('created_at', 'asc')
                ->get();
            return view('messages.index', compact('users', 'messages', 'posts'));
        } else {
            $admin = \App\Models\User::where('role', 'admin')->first();
            
            // Mark messages from admin as read
            if ($admin) {
                \App\Models\Message::where('sender_id', $admin->id)
                    ->where('receiver_id', $user->id)
                    ->whereNull('read_at')
                    ->update(['read_at' => now()]);
            }

            $messages = \App\Models\Message::where(function($q) use ($user, $admin) {
                if ($admin) {
                    $q->where('sender_id', $user->id)->where('receiver_id', $admin->id);
                }
            })->orWhere(function($q) use ($user, $admin) {
                if ($admin) {
                    $q->where('sender_id', $admin->id)->where('receiver_id', $user->id);
                }
            })->orderBy('created_at', 'asc')->get();
            
            $customer = \App\Models\Customer::with(['bills' => function($q) {
                $q->orderBy('billing_date', 'desc');
            }, 'waterUsages'])->find($user->customer_id);

            return view('messages.consumer', compact('admin', 'messages', 'customer', 'posts'));
        }
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        if (auth()->user()->role === 'consumer') {
            $admin = \App\Models\User::where('role', 'admin')->first();
            if (!$admin || $request->receiver_id != $admin->id) {
                return back()->with('error', 'You are only allowed to message the admin.');
            }
        }

        \App\Models\Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        return back()->with('success', 'Message sent.');
    }

    public function storePost(\Illuminate\Http\Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            return back()->with('error', 'Only admins can post announcements.');
        }

        $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'required|string',
        ]);

        \App\Models\Post::create([
            'admin_id' => auth()->id(),
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return back()->with('success', 'Post published successfully.');
    }

    public function markRead(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'partner_id' => 'required|exists:users,id'
        ]);
        
        \App\Models\Message::where('sender_id', $request->partner_id)
            ->where('receiver_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
            
        return response()->json(['success' => true]);
    }
}
