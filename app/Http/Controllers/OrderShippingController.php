<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderShippingRequest;
use App\Http\Requests\UpdateOrderShippingRequest;
use App\Models\OrderShipping;

class OrderShippingController extends Controller
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
     * @param  \App\Http\Requests\StoreOrderShippingRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrderShippingRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OrderShipping  $orderShipping
     * @return \Illuminate\Http\Response
     */
    public function show(OrderShipping $orderShipping)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OrderShipping  $orderShipping
     * @return \Illuminate\Http\Response
     */
    public function edit(OrderShipping $orderShipping)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOrderShippingRequest  $request
     * @param  \App\Models\OrderShipping  $orderShipping
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrderShippingRequest $request, OrderShipping $orderShipping)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OrderShipping  $orderShipping
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrderShipping $orderShipping)
    {
        //
    }
}
