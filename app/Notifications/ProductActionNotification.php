<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProductActionNotification extends Notification
{
    use Queueable;

    protected $action;
    protected $product;
    public $actor;

    public function __construct(string $action, $product, $actor)
    {
        $this->action = $action; 
        $this->product = $product; 
        $this->actor = $actor; 
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
            'actor' => [
                'id' => $this->actor->id,
                'name' => $this->actor->name,
            ],
        ];
    }
}

