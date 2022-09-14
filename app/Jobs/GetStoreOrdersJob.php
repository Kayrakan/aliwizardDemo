<?php

namespace App\Jobs;

use AliexpressSolutionOrderGetRequest;

//use App\Models\Order;
use App\Models\Order;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use OrderQuery;

class GetStoreOrdersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Store
     */
    private $store;
    /**
     * @var bool
     */
    private $getAll;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Store $store, bool $getAll = false)
    {

        $this->store = $store;
        $this->getAll = $getAll;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!$this->store->checkAccessToken()) {
            $this->fail();
            return;
        }
        $start_date = Carbon::now()->subYears(3)->format('Y-m-d H:i:s');

        if ($this->store->orders->count() > 0 && !$this->getAll) {
            $start_date = $this->store->orders()->orderBy('order_date', 'desc')->first()->order_date;
        }
        dump($start_date);
//        return;

        $orders_p1 = $this->getOrders($start_date, '1');


        $totalOrders = $orders_p1->result->total_count;
        $totalPages = $orders_p1->result->total_page;
        dump("we have {$totalOrders} orders in {$totalPages} pages");

        if ($totalOrders == 0) {
            dump("no new orders found");
            return;
        }
        $this->importOrders($orders_p1->result->target_list->order_dto);

        for ($i = 2; $i <= $totalPages; $i++) {

            dump("getting orders. page {$i} of {$totalPages}. ");
            $this->importOrders($this->getOrders($start_date, $i)->result->target_list->order_dto);
        }
    }

    private function importOrders(array $orders): void
    {
        foreach ($orders as $order) {
            Order::updateOrCreate([
                'store_id' => $this->store->id,
                'order_id' => $order->order_id,

            ], [
                    'order_status' => $order->order_status,
                    'order_date' => $order->gmt_create,
                    'name' => $order->buyer_signer_fullname,
                    'end_reason' => $order->end_reason,
                    'buyer_login_id' => $order->buyer_login_id,
                    'image' => $order->product_list->order_product_dto[0]->product_img_url
                ]
            );

        }
    }

    private function getOrders($start_date, $page = '1')
    {
        $param0 = new OrderQuery;
        $param0->create_date_end = Carbon::now()->format('Y-m-d H:i:s');
        $param0->create_date_start = $start_date;
        $param0->page_size = "2000";
        $param0->order_status_list = ['WAIT_SELLER_SEND_GOODS', 'SELLER_PART_SEND_GOODS', 'PLACE_ORDER_SUCCESS',
            'WAIT_BUYER_ACCEPT_GOODS', 'FINISH', 'IN_FROZEN', 'IN_ISSUE'];
        $param0->current_page = $page;
        return $this->store->getOrders($param0);
    }
}
//test
