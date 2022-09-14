<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderTag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        return response()->json(QueryBuilder::for(Auth::user()->orders())
            ->with('tag')
            ->with('store')
            ->defaultSort('order_id')
            ->allowedSorts('name', '-name', 'order_id', 'order_status', 'country', 'tag', 'store')
            ->allowedFilters([
                AllowedFilter::exact('store_id'),
                'order_id','company', 'name', 'tag','order_status','country', 'store'
            ])
            ->with('orderProducts')
            ->paginate($request->query('page_size',10))
            ->appends(request()->query()));
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param StoreOrderRequest $request
     * @return Response
     */
    public function store(StoreOrderRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Order $order
     * @return Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateOrderRequest $request
     * @param Order $order
     * @return JsonResponse
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $order->fill($request->validated());
        $order->save();
        return response()->json(['success' => 1], 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Order $order
     * @return Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
