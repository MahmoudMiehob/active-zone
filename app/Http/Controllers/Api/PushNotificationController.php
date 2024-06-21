<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use App\Http\Controllers\Controller;
use Kreait\Firebase\Messaging\CloudMessage;

class PushNotificationController extends Controller
{
    public function sendPushNotification()
    {
        $firebase = (new Factory)
        ->withServiceAccount(base_path('config/google-services.json'));
    
    $messaging = $firebase->createMessaging();
    
    $message = CloudMessage::fromArray([
        'notification' => [
            'title' => 'Hello from Firebase!',
            'body' => 'This is a test notification.'
        ],
        'topic' => 'global'
    ]);
    
    $messaging->send($message);
    
    return response()->json(['message' => 'Push notification sent successfully']);
    }
}
