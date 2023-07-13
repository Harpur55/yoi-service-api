<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSellerNotificationRequest;
use App\Http\Requests\UpdateSellerNotificationRequest;
use App\Models\SellerNotification;

class SellerNotificationController extends Controller
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
     * @param  \App\Http\Requests\StoreSellerNotificationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSellerNotificationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SellerNotification  $sellerNotification
     * @return \Illuminate\Http\Response
     */
    public function show(SellerNotification $sellerNotification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SellerNotification  $sellerNotification
     * @return \Illuminate\Http\Response
     */
    public function edit(SellerNotification $sellerNotification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSellerNotificationRequest  $request
     * @param  \App\Models\SellerNotification  $sellerNotification
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSellerNotificationRequest $request, SellerNotification $sellerNotification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SellerNotification  $sellerNotification
     * @return \Illuminate\Http\Response
     */
    public function destroy(SellerNotification $sellerNotification)
    {
        //
    }
}
