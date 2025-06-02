<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdviceRequestAccepted extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Yêu cầu tư vấn của bạn đã được tiếp nhận')
            ->greeting('Xin chào ' . $notifiable->name . ',')
            ->line('Yêu cầu tư vấn của bạn đã được tiếp nhận bởi người hỗ trợ.')
            ->line('Chúng tôi sẽ sớm lên lịch hẹn và liên hệ lại với bạn.')
            ->action('Xem chi tiết', url('/advice/history'))
            ->line('Cảm ơn bạn đã tin tưởng SheZen!');
    
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
