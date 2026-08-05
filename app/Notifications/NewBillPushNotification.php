<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class NewBillPushNotification extends Notification
{
    use Queueable;

    public $amount;
    public $dueDate;

    public function __construct($amount, $dueDate)
    {
        $this->amount = $amount;
        $this->dueDate = $dueDate;
    }

    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('New Water Bill')
            ->icon('/images/system_bg.png')
            ->body('A new bill of ' . number_format($this->amount, 2) . ' has been issued. Due on ' . $this->dueDate . '.')
            ->action('View Bills', 'view_bills')
            ->data(['url' => url('/billing')]);
    }
}
