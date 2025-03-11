<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderNotification extends Notification
{
    use Queueable;

    protected $order;
    protected $type;

    /**
     * إنشاء الإشعار
     */
    public function __construct($order, $type)
    {
        $this->order = $order;
        $this->type = $type;
    }

    /**
     * القنوات التي سيتم إرسال الإشعار من خلالها
     */
    public function via($notifiable)
    {
        return $this->type === 'user' ? ['mail', 'database'] : ['database'];
    }

    /**
     * إشعار اليوزر عبر البريد
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('تم استلام طلبك!')
            ->greeting('مرحباً ' . $notifiable->name)
            ->line('تم استلام طلبك رقم #' . $this->order->id . ' بنجاح.')
            ->action('عرض الطلب', url('/orders/' . $this->order->id))
            ->line('شكراً لتسوقك معنا!');
    }

    /**
     * إشعار اليوزر أو الأدمن عبر قاعدة البيانات
     */
    public function toDatabase($notifiable)
    {
        if ($this->type === 'admin') {
            return [
                'message' => "طلب جديد رقم #{$this->order->id} بحاجة لمراجعتك.",
                'order_id' => $this->order->id,
                'type' => 'admin',
            ];
        } else {
            return [
                'message' => "تم استلام طلبك رقم #{$this->order->id}. سيتم معالجته قريبًا!",
                'order_id' => $this->order->id,
                'type' => 'user',
            ];
        }
    }
}
