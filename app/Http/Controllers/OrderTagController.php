<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderTagsRequest;
use App\Http\Requests\UpdateOrderTagsRequest;
use App\Models\OrderTag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderTagController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(OrderTag::class, 'order_tag');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(Auth::user()->orderTags()->with('order')->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'order_count' => $item->order->count()
            ];
        }));
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
     * @param \App\Http\Requests\StoreOrderTagsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreOrderTagsRequest $request)
    {
        $request->validated();
        return response()->json([
            Auth::user()->orderTags()->create(['name' => $request->input('name')])],
            201);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateOrderTagsRequest $request
     * @param \App\Models\OrderTag $orderTag
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateOrderTagsRequest $request, OrderTag $orderTag)
    {
        $request->validated();
        $orderTag->name = $request->input('name');
        $orderTag->save();
        return response()->json($orderTag->save(), 204);

    }



    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\OrderTag $orderTag
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(OrderTag $orderTag)
    {
        return response()->json($orderTag->deleteOrFail());
    }

}
