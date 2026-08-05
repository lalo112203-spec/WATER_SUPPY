<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Customer;

class NotificationCheckController extends Controller
{
    public function check(Request $request, $customer_id)
    {
        $customer = Customer::find($customer_id);
        if (!$customer || !$customer->user) {
            return response()->json(['has_new_bill' => false]);
        }

        // Find unread messages for this user that talk about a new bill
        $unreadMessages = Message::where('receiver_id', $customer->user->id)
            ->whereNull('read_at')
            ->where('message', 'like', '%new bill%')
            ->get();

        if ($unreadMessages->count() > 0) {
            // Optionally mark them as read so we don't notify again
            // For now, we will mark them as read when fetched so it only notifies once per bill
            foreach ($unreadMessages as $msg) {
                $msg->update(['read_at' => now()]);
            }
            
            return response()->json([
                'has_new_bill' => true,
                'message' => 'You have a new water bill available.'
            ]);
        }

        return response()->json(['has_new_bill' => false]);
    }
}
