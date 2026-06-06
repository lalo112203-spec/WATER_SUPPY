<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class BillPaidPushNotification extends Notification
{
    use Queueable;

    public $amount;
    public $date;

    public function __construct($amount, $date)
    {
        $this->amount = $amount;
        $this->date = $date;
    }

    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Bill Paid')
            ->icon('/images/system_bg.png') // Fallback icon, could use actual app logo
            ->body('Your bill for ' . number_format($this->amount, 2) . ' on ' . $this->date . ' has been successfully paid.')
            ->action('View Bills', 'view_bills')
            ->data(['url' => url('/billing')]);
    }
}
