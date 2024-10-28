<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProductActionNotification extends Notification
{
    use Queueable;

    protected $action;
    protected $product;

    public function __construct(string $action, $product)
    {
        $this->action = $action; 
        $this->product = $product; 
    }

    public function via($notifiable)
    {
        return ['database']; 
    }

    public function toArray($notifiable)
    {
        return [
            'action' => $this->action,
            'product' => $this->product, 
        ];
    }
}

