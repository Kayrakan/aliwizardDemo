<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderStatusRequest;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Models\OrderTag;

class OrderStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StoreOrderStatusRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrderStatusRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OrderTag  $orderStatus
     * @return \Illuminate\Http\Response
     */
    public function show(OrderTag $orderStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OrderTag  $orderStatus
     * @return \Illuminate\Http\Response
     */
    public function edit(OrderTag $orderStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOrderStatusRequest  $request
     * @param  \App\Models\OrderTag  $orderStatus
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrderStatusRequest $request, OrderTag $orderStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OrderTag  $orderStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrderTag $orderStatus)
    {
        //
    }
}
