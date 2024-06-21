<?php

namespace App\Listeners;

use Kreait\Firebase\Factory;
use App\Events\MinisurviceCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Kreait\Firebase\Messaging\CloudMessage;

class SendFirebaseNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MinisurviceCreated $event): void
    {
        $firebase = (new Factory)
            ->withServiceAccount(base_path('config/firebase_credentials.json'));

        $messaging = $firebase->createMessaging();

        $message = CloudMessage::fromArray([
            'notification' => [
                'title' => 'New Service Created',
                'body' => "Service '{$event->service->name}' has been created!",
            ],
            'topic' => 'global',
        ]);

        $messaging->send($message);
    }
    
}
