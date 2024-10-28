<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use Illuminate\Support\Facades\Auth;

class ProductActionNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $product;
    protected $action;
    
    public function __construct($product, $action)
    {
        $this->product = $product;
        $this->action = $action;
    }
    

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
{
    return ['database', 'broadcast'];
}


    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
  // In your ProductActionNotification class
public function toArray($notifiable)
{
    \Log::info('Notification sent to user: ' . $notifiable->id); // Log when the notification is sent
    return [
        'message' => "{$this->action} performed on {$this->product->name}",
        'product_id' => $this->product->id,
        'action_by' => Auth::user()->name,
    ];
}

    
}
