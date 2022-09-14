<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class GetSingleOrderDetailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Order
     */
    private $order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        dump("getting order info for {$this->order->order_id}");

        $orderInfo = $this->order->store->getOrderDetail($this->order->order_id);
        collect($orderInfo->result->data->child_order_ext_info_list->global_aeop_tp_order_product_info_dto)
            ->each(function ($item) use (&$totalProducts) {
                $totalProducts += $item->quantity;
            });
        $this->order->total_quantity = $totalProducts;
        $this->order->product_count = count($orderInfo->result->data->child_order_ext_info_list->global_aeop_tp_order_product_info_dto);
        $map = [
            // field in order model => field in aliexpress json
            'country' => 'result.data.receipt_address.country',
            'amount' => 'result.data.order_amount.amount',

        ];

        foreach ($map as $key => $value) {
            $this->order->{$key} = (function ($obj, $path) {
                foreach (explode('.', $path) as $e) {
                    $obj = $obj->{$e};
                }
                return $obj;
            })($orderInfo, $value);
        }
        $this->order->save();
        foreach ($orderInfo->result->data->child_order_ext_info_list->global_aeop_tp_order_product_info_dto as $op) {
            $order_product = OrderProduct::updateOrCreate([
                'store_id' => $this->order->store->id,
                'product_id' => $op->product_id,
            ],
                [
                    'product_name' => $op->product_name,
                    'category_id' => (int)$op->category_id,
                ]
            );
            if (DB::table('order_order_product')
                    ->whereOrderId($this->order->id)
                    ->whereOrderProductId($order_product->id)
                    ->count() == 0) {
                $this->order->orderProducts()->attach($order_product, [
                    'title' => $op->product_name,
                    'sku' => $op->sku,
                    'quantity' => $op->quantity,
                    'price' => $op->unit_price->cent / $op->unit_price->cent_factor
                ]);
            }

        }

    }
}
