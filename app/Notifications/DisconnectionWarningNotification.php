<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class DisconnectionWarningNotification extends Notification
{
    use Queueable;

    public $unpaidCount;
    public $totalAmount;

    public function __construct(int $unpaidCount, float $totalAmount)
    {
        $this->unpaidCount = $unpaidCount;
        $this->totalAmount = $totalAmount;
    }

    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('WARNING: Water Disconnection Notice')
            ->icon('/images/system_bg.png')
            ->body('Urgent: You have ' . $this->unpaidCount . ' unpaid bill(s) totaling ₱' . number_format($this->totalAmount, 2) . '. Settle immediately to avoid disconnection.')
            ->action('View Bills', 'view_bills')
            ->data(['url' => url('/dashboard')]);
    }
}
