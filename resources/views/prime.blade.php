<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>aliexpress manager</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    {{--    <link rel="stylesheet" href="{{ mix('css/app.css') }}">--}}
    <link rel="stylesheet" href="{{ mix('css/prime.css') }}">

    <!-- Scripts -->
    @routes
    <script src="{{ mix('js/prime.js') }}" defer></script>

    <script src="https://js.pusher.com/beams/1.0/push-notifications-cdn.js"></script>

    <script>

        const beamsClient = new PusherPushNotifications.Client({
            instanceId: '455d1efc-5bb6-4637-97b9-3e3711b526ce',
        });

        beamsClient.start()
            .then((beamsClient) => beamsClient.getDeviceId())
            .then((deviceId) =>
                console.log("Successfully registered with Beams. Device ID:", deviceId)
            )
            .then(() => beamsClient.addDeviceInterest('hello'))
            .then(() => beamsClient.getDeviceInterests())
            .then(() => console.log('Successfully registered and subscribed!'))
            .catch(console.error);



        beamsClient.onNotificationReceived = ({
              pushEvent,
              payload,
              handleNotification,
          }) => {
            // Your custom notification handling logic here 🛠️
            // This method triggers the default notification handling logic offered by
            // the Beams SDK. This gives you an opportunity to modify the payload.
            // E.g. payload.notification.title = "A client-determined title!"
            console.log(payload);
            console.log(pushEvent);
            console.log(handleNotification);
            pushEvent.waitUntil(handleNotification(payload));
        };

    </script>

</head>
<body class="font-sans antialiased">
    <div id="app">
        <div class="preloader">
            <div class="preloader-content">
            </div>
        </div>
    </div>
@env ('local')
    <script src="http://localhost:3000/browser-sync/browser-sync-client.js"></script>
@endenv
</body>
</html>
