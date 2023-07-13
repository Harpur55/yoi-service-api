<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWishlistDetailRequest;
use App\Http\Requests\UpdateWishlistDetailRequest;
use App\Models\WishlistDetail;

class WishlistDetailController extends Controller
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
     * @param  \App\Http\Requests\StoreWishlistDetailRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWishlistDetailRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WishlistDetail  $wishlistDetail
     * @return \Illuminate\Http\Response
     */
    public function show(WishlistDetail $wishlistDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WishlistDetail  $wishlistDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(WishlistDetail $wishlistDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateWishlistDetailRequest  $request
     * @param  \App\Models\WishlistDetail  $wishlistDetail
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateWishlistDetailRequest $request, WishlistDetail $wishlistDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WishlistDetail  $wishlistDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(WishlistDetail $wishlistDetail)
    {
        //
    }
}
