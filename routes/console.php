<?php

use App\Jobs\GetSingleOrderDetailJob;
use App\Jobs\GetStoreOrdersJob;
use App\Models\Order;
use App\Models\Store;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('orderd', function () {
//    $s= Store::first();
//    $od = $s->getOrderDetail(8143533570294569);
//    file_put_contents('single_order_open.json',json_encode($od, JSON_PRETTY_PRINT));

    $start_date = Carbon::now()->subYears(3)->format('Y-m-d H:i:s');
    $param0 = new OrderQuery;
    $param0->create_date_end = Carbon::now()->format('Y-m-d H:i:s');
    $param0->create_date_start = $start_date;
    $param0->page_size = "2000";
    $param0->order_status_list = ['WAIT_SELLER_SEND_GOODS', 'SELLER_PART_SEND_GOODS', 'PLACE_ORDER_SUCCESS',
        'WAIT_BUYER_ACCEPT_GOODS', 'FINISH', 'IN_FROZEN', 'IN_ISSUE'];
    $param0->current_page = '1';
    $orders = Store::first()->getOrders($param0);
    file_put_contents('orders_all.json', json_encode($orders, JSON_PRETTY_PRINT));
});
Artisan::command('mp', function () {
    $u = new User();
    $u = User::first();
    $r = $u->orders()->whereIn('end_reason', ['', 'buyer_confirm_goods'])->sum('amount');
    dump($r);
    exit();
    (new GetStoreOrdersJob(Store::first(), true))->handle();
    $order = Order::find(1);
    $order->order_date = '2021-12-08 01:01:17';
    Order::updateOrCreate([
        'store_id' => 1,
        'order_id' => 8143533570294569,

    ], [
        'order_date' => '2021-12-08 01:01:19'
    ]);
    return;
    $e = file_get_contents('error.json');
    $e = json_decode($e);
    dump(unserialize($e->data->command));
    return;
    $user = User::first();
    dump($user->orders()->where(['order_status' => 'WAIT_BUYER_ACCEPT_GOODS'])->count());
});

Artisan::command('gso {id}', function ($id) {
    $store = Store::find($id);
    dump($store->store_name);
    (new GetStoreOrdersJob($store))->handle();
});

Artisan::command('beams', function () {


    $beamsClient = new \Pusher\PushNotifications\PushNotifications(array(
        "instanceId" => "455d1efc-5bb6-4637-97b9-3e3711b526ce",
        "secretKey" => "8388A6032897B140DE9C9A1F1D1EE186F12CBB90B1239F1C0C2CA1AEE6C2A227",
    ));

    $publishResponse = $beamsClient->publishToInterests(
        array('hello'), // we pass the user id here
        array("web" => array("notification" => array(
            "title" => "Hello",
            "body" => "Hello, World!",
            "deep_link" => "https://www.pusher.com",
        )),
        ));

    var_dump($publishResponse);
    var_dump('done');
});
