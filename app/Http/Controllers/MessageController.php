<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $adminId = $user->role === 'admin' ? $user->id : ($user->customer ? $user->customer->admin_id : null);
        
        // Only show posts from the relevant admin
        $postsQuery = \App\Models\Post::with('admin')->orderBy('created_at', 'desc');
        if ($adminId) {
            $postsQuery->where('admin_id', $adminId);
        }
        $posts = $postsQuery->get();

        if ($user->role === 'admin') {
            // Admins only see their own customers' messages
            $myCustomerIds = \App\Models\Customer::where('admin_id', $user->id)->pluck('id');
            $myUserIds = \App\Models\User::whereIn('customer_id', $myCustomerIds)->pluck('id');

            $users = \App\Models\User::where('role', 'consumer')
                ->whereIn('id', $myUserIds)
                ->withCount(['sentMessages as unread_count' => function ($query) use ($user) {
                    $query->where('receiver_id', $user->id)->whereNull('read_at');
                }])
                ->orderByDesc('unread_count')
                ->get();

            $messages = \App\Models\Message::where(function($q) use ($user, $myUserIds) {
                    $q->where('sender_id', $user->id)->whereIn('receiver_id', $myUserIds);
                })
                ->orWhere(function($q) use ($user, $myUserIds) {
                    $q->whereIn('sender_id', $myUserIds)->where('receiver_id', $user->id);
                })
                ->with(['sender', 'receiver'])
                ->orderBy('created_at', 'asc')
                ->get();

            return view('messages.index', compact('users', 'messages', 'posts'));
        } else {
            // Consumer side: find THEIR admin
            $myAdminId = $user->customer ? $user->customer->admin_id : \App\Models\User::where('role', 'admin')->first()->id;
            $admin = \App\Models\User::find($myAdminId);
            
            // Mark all received messages as read
            \App\Models\Message::where('receiver_id', $user->id)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            $messages = \App\Models\Message::where(function($q) use ($user, $myAdminId) {
                $q->where('sender_id', $user->id)->where('receiver_id', $myAdminId);
            })->orWhere(function($q) use ($user, $myAdminId) {
                $q->where('sender_id', $myAdminId)->where('receiver_id', $user->id);
            })->orderBy('created_at', 'asc')->get();
            
            return view('messages.consumer', compact('admin', 'messages', 'posts'));
        }
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        if (auth()->user()->role === 'consumer') {
            $myAdminId = auth()->user()->customer ? auth()->user()->customer->admin_id : \App\Models\User::where('role', 'admin')->first()->id;
            if ($request->receiver_id != $myAdminId) {
                return back()->with('error', 'You are only allowed to message your assigned admin.');
            }
        }

        \App\Models\Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        if (auth()->user()->role === 'admin') {
            return redirect()->route('messages.index', ['select_user' => $request->receiver_id]);
        }

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
        
        if (auth()->user()->role === 'admin') {
            $adminIds = \App\Models\User::where('role', 'admin')->pluck('id');
            \App\Models\Message::where('sender_id', $request->partner_id)
                ->whereIn('receiver_id', $adminIds)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        } else {
            \App\Models\Message::where('sender_id', $request->partner_id)
                ->where('receiver_id', auth()->id())
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }
            
        return response()->json(['success' => true]);
    }

    public function update(\Illuminate\Http\Request $request, \App\Models\Message $message)
    {
        if (auth()->id() !== $message->sender_id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate(['message' => 'required|string']);
        $message->update(['message' => $request->message]);

        return response()->json(['success' => true, 'new_text' => $message->message]);
    }

    public function destroy(\App\Models\Message $message)
    {
        if (auth()->id() !== $message->sender_id && auth()->user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $message->delete();
        return response()->json(['success' => true]);
    }
}
