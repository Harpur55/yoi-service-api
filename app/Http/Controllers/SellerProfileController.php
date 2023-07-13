<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSellerProfileRequest;
use App\Http\Requests\UpdateSellerProfileRequest;
use App\Models\SellerProfile;

class SellerProfileController extends Controller
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
     * @param  \App\Http\Requests\StoreSellerProfileRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSellerProfileRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SellerProfile  $sellerProfile
     * @return \Illuminate\Http\Response
     */
    public function show(SellerProfile $sellerProfile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SellerProfile  $sellerProfile
     * @return \Illuminate\Http\Response
     */
    public function edit(SellerProfile $sellerProfile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSellerProfileRequest  $request
     * @param  \App\Models\SellerProfile  $sellerProfile
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSellerProfileRequest $request, SellerProfile $sellerProfile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SellerProfile  $sellerProfile
     * @return \Illuminate\Http\Response
     */
    public function destroy(SellerProfile $sellerProfile)
    {
        //
    }
}
