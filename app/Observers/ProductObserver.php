<?php

namespace App\Observers;

use App\Models\Product; 
use App\Models\User;
use App\Notifications\ProductActionNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;


class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product)
    {
        $admins = User::whereHas('roles', function ($query) {
            $query->where('name', 'Admin'); 
        })->get();
        $actor = auth()->user();
        foreach ($admins as $admin) {
            $notification = new ProductActionNotification('Product created', $product , $actor);
            Notification::send($admin, $notification);
        }
    }
     

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product)
    {
        $admins = User::whereHas('roles', function ($query) {
            $query->where('name', 'Admin'); 
        })->get();
        $actor = Auth::user(); 
        foreach ($admins as $admin) {
            $notification = new ProductActionNotification('updated', $product,  $actor);
            Notification::send($admin, $notification);
        }
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product)
    {
        $admins = User::whereHas('roles', function ($query) {
            $query->where('name', 'Admin'); 
        })->get();
        $actor = Auth::user(); 
        foreach ($admins as $admin) {
            $notification = new ProductActionNotification('deleted', $product,  $actor);
            Notification::send($admin, $notification);
        }
    }
}
