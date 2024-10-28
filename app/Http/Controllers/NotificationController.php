<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function index()
    {  \Log::info('Notifications fetched.');
        $notifications = Auth::user()->notifications()->latest()->get();
        // \Log::info('Fetched notifications:', $notifications->toArray());
        return response()->json($notifications);
    }
}

