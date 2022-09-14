<?php

namespace App\Http\Controllers;

use App\Http\Requests\DashboardRequest;
use App\Models\Order;
use App\Models\OrderTag;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;
use Nette\Utils\DateTime;
use PhpParser\Node\Scalar\String_;
use Ramsey\Uuid\Type\Time;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class DashboardController extends Controller
{


    private $date;
    private $store;

    public function __construct(Request $request)
    {

        $this->date = Carbon::parse($request->get('time_interval'));
        $this->store = explode(',', $request->get('store'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(DashboardRequest $request )
    {

        return response()->json([
            //
        ]);
//        return response()->json([
//            'orders_count' => Auth::user()->orders->where(['order_date', '>=', $this->date ])->count(),
//            'orders_not_shipped_count' =>  Auth::user()->orders()->where(['order_status' => 'WAIT_SELLER_SEND_GOODS', 'orders.store_id' => $this->store ])->count(),
//            'orders_not_shipped_amount' => Auth::user()->orders()
//                ->where(['order_status' => 'WAIT_SELLER_SEND_GOODS'])->sum('amount'),
//            'orders_total_amount' => Auth::user()->orders()->where('order_date', '>', $this->date )->whereIn('orders.store_id', $this->store)->
//            whereIn('end_reason',['','buyer_confirm_goods'])->sum('amount'),
//            'orders_waiting_buyer_accept_count' => Auth::user()->orders()
//                ->where(['order_status' => 'WAIT_BUYER_ACCEPT_GOODS'])->count(),
//            'orders_waiting_buyer_accept_amount' => Auth::user()->orders()
//                ->where(['order_status' => 'WAIT_BUYER_ACCEPT_GOODS'])->sum("amount"),
//            'customers_count' => Auth::user()->orders()->distinct("name")->count(),
//            'last_orders' => Auth::user()->orders()->where(['order_status'=>'WAIT_SELLER_SEND_GOODS'])
//                ->orderBy("order_date",'desc')->limit(50)->get(),
//            'stores' => $request->get('store') ? Auth::user()->stores()->where(['id' => $store])->select(['store_name', 'id'])->get() : Auth::user()->stores()->select(['store_name','id'])->get()
//        ]);
    }

    public function orders_count(DashboardRequest $request) {

        //counting store(s') orders upon request
        return response()->json([

            'orders_count' => Auth::user()->orders()->where('order_date', '>', $this->date )->whereIn('orders.store_id', $this->store)->count(),

        ]);
    }


    public function orders_not_shipped_count(DashboardRequest $request) {

        //counting store(s') orders that are not shipped upon request

        return response()->json([
            'orders_not_shipped_count' =>  Auth::user()->orders()
                ->where('order_date', '>', $this->date )->whereIn('orders.store_id', $this->store)
                ->where(['order_status' => 'WAIT_SELLER_SEND_GOODS' ])->count(),

        ]);

    }

    public function orders_not_shipped_amount(DashboardRequest $request) {

        //summing the amount of store(s') orders that are not shipped upon request
        return response()->json([
            'orders_not_shipped_amount' => Auth::user()->orders()
                ->where('order_date', '>', $this->date )->whereIn('orders.store_id', $this->store)
                ->where(['order_status' => 'WAIT_SELLER_SEND_GOODS'])->sum('amount'),

        ]);

    }

    public function orders_total_amount(DashboardRequest $request) {

        //summing the amount of store(s') orders upon request
        return response()->json([

            'orders_total_amount' => Auth::user()->orders()->where('order_date', '>', $this->date )->whereIn('orders.store_id', $this->store)->
            whereIn('end_reason',['','buyer_confirm_goods'])->sum('amount'),

        ]);

    }

    public function orders_waiting_buyer_accept_count(DashboardRequest $request) {

        //counting store(s') orders that has a condition of 'watiign buyer_accept ' upon request
        return response()->json([

            'orders_waiting_buyer_accept_count' => Auth::user()->orders()
                ->where('order_date', '>', $this->date )->whereIn('orders.store_id', $this->store)
                ->where(['order_status' => 'WAIT_BUYER_ACCEPT_GOODS'])->count(),

        ]);

    }

    public function orders_waiting_buyer_accept_amount(DashboardRequest $request) {

        //summing the amount of store(s') orders that has a condition of 'watiign buyer_accept ' upon request
        return response()->json([

            'orders_waiting_buyer_accept_amount' => Auth::user()->orders()
                ->where('order_date', '>', $this->date )->whereIn('orders.store_id', $this->store)
                ->where(['order_status' => 'WAIT_BUYER_ACCEPT_GOODS'])->sum("amount"),

        ]);

    }


    public function customers_count(DashboardRequest $request) {

        //counting store(s') customers upon request
        return response()->json([
            'customers_count' => Auth::user()->orders()
                ->where('order_date', '>', $this->date )->whereIn('orders.store_id', $this->store)
                ->distinct("name")->count(),

        ]);

    }

    public function last_orders(DashboardRequest $request) {

        //summing the amount of all store(s') orders upon request

        return response()->json(QueryBuilder::for(Auth::user()->orders()
            ->where('order_date', '>', $this->date )->whereIn('orders.store_id', $this->store)
            ->where(['order_status'=>'WAIT_SELLER_SEND_GOODS'])->with('tag')
            ->orderBy("order_date",'desc')->limit(50)

        )
            ->defaultSort('tag')
            ->allowedSorts('name', '-name', 'amount', 'order_id', 'order_status', 'country', 'tag')
            ->allowedFilters([
                AllowedFilter::exact('store_id'),
                'order_id','company', 'name', 'tag','order_status','country','store'
            ])->get());


    }

    public function get_stores(Request $request) {

//        loading stores upon request
        return response()->json([
            'stores' => Auth::user()->stores()->select(['store_name','id'])->get()
        ]);
    }

    public function country_totalorder_with_revenue(DashboardRequest $request) {

        //summing the amount of store(s') orders in relation to their countries  upon request

        $total_orders = Auth::user()->orders()->where('order_date', '>', $this->date )->whereIn('orders.store_id', $this->store)->count();
        $total_revenue = Auth::user()->orders()->where('order_date', '>', $this->date )->whereIn('orders.store_id', $this->store)->
        whereIn('end_reason',['','buyer_confirm_goods'])->sum('amount');
        return response()->json([

            'country_totalorder_with_revenue' => Auth::user()->orders()
                ->where('order_date', '>', $this->date )->whereIn('orders.store_id', $this->store)
                ->get()
                ->groupBy('country')
                ->map(function ($order ) use ($total_revenue, $total_orders) {
                    return [
                        'total'=> $order->count(),
                        'revenue'=>round($order->sum('amount'),2),
                        'total_perc'=> round(($order->count() * 100) / $total_orders, 2),
                        'revenue_perc'=> round(($order->sum('amount') * 100) / $total_revenue, 2)
                    ];

                })

        ]);

    }
    public function orders_based_on_tags(DashboardRequest $request){

        //summing the amount of store(s') orders in relation to their tags  upon request

        $total_orders = Auth::user()->orders()->where('order_date', '>', $this->date )->whereIn('orders.store_id', $this->store)->count();
        $total_revenue = Auth::user()->orders()->where('order_date', '>', $this->date )->whereIn('orders.store_id', $this->store)->
        whereIn('end_reason',['','buyer_confirm_goods'])->sum('amount');

        return response()->json([

            'orders_based_on_tags' => Auth::user()->orders()
                ->where('order_date', '>', $this->date )->whereIn('orders.store_id', $this->store)
                ->get()
                ->groupBy('tag')
                ->map(function ($order ) use ($total_revenue, $total_orders) {
                    return [
                        'tag' =>$order->first()->tag ? OrderTag::where('id',$order->first()->tag)->first()->name : '',
                        'total'=> $order->count(),
                        'revenue'=>round($order->sum('amount'),2),
                        'total_perc'=> round(($order->count() * 100) / $total_orders, 2),
                        'revenue_perc'=> round(($order->sum('amount') * 100) / $total_revenue, 2)
                    ];

                })

        ]);

    }

    public function top_ten(DashboardRequest $request) {

        //loads top 10 sold products  upon request

        return response()->json([

            'top_ten' => Auth::user()->order_products()->join('order_order_product', 'order_product_id' ,'=', 'order_products.id')
                ->join('orders', 'orders.id' ,'=', 'order_order_product.order_id')
                ->where('orders.order_date', '>', $this->date )
                ->whereIn('order_products.store_id', $this->store)->get()->sortBy('product_id')
                ->groupBy('product_id')
                ->map(function ($product_id) {
                    return collect([
                        'count' => $product_id->count(),
                        'title' => $product_id->first()->product_name
                    ]);


                })->sortByDesc('count')->take(10)

        ]);
    }

    public function monthly_sell(Request $request) {


        //providing monthly sales  upon request

        return response()->json([
            'monthly_sell' => Auth::user()->orders()->select(
            DB::raw('sum(amount) as sums'),
            DB::raw('count(*) as total'),
            DB::raw("DATE_FORMAT(order_date, '%M') as months"),
            DB::raw("DATE_FORMAT(order_date, '%Y') as years")

            )
            ->groupBy('months')->orderBy('months')
            ->get()
        ]);
    }

    public function number_of_products_sold(DashboardRequest $request)
    {

        //giving number of products sold distinctly.

        return response()->json([
            'number_of_products_sold' => Auth::user()->order_products()->join('order_order_product','order_product_id','=','order_products.id')
                ->join('orders', 'orders.id' ,'=', 'order_order_product.order_id')
                ->where('order_date', '>', $this->date)->whereIn('orders.store_id', $this->store)
                ->distinct("product_id")->count(),

        ]);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }





}
