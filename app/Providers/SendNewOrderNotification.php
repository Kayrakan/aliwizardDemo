<?php

namespace App\Providers;

use App\Events\NewOrderReceived;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Pusher\PushNotifications\PushNotifications;

class SendNewOrderNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\NewOrderReceived  $event
     * @return void
     */
    public function handle(NewOrderReceived $event)
    {
        //
        $beamsClient = new PushNotifications(array(
            "instanceId" => env('PUSHER_BEAMS_INSTANCE_KEY'),
            "secretKey" => env('PUSHER_BEAMS_SECRET_KEY'),
        ));

        $publishResponse = $beamsClient->publishToInterests(
            array('hello'), // we pass the user id here
            array("web" => array("notification" => array(
                "title" => "New order",
                "body" => "A new order has been received !",
                "deep_link" => "https://www.pusher.com",
            )),
            ));
        var_dump('notification send');
    }
}
