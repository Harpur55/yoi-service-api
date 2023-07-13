<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResetPasswordRequest;
use App\Http\Requests\UpdateResetPasswordRequest;
use App\Models\ResetPassword;

class ResetPasswordController extends Controller
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
     * @param  \App\Http\Requests\StoreResetPasswordRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreResetPasswordRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ResetPassword  $resetPassword
     * @return \Illuminate\Http\Response
     */
    public function show(ResetPassword $resetPassword)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ResetPassword  $resetPassword
     * @return \Illuminate\Http\Response
     */
    public function edit(ResetPassword $resetPassword)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateResetPasswordRequest  $request
     * @param  \App\Models\ResetPassword  $resetPassword
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateResetPasswordRequest $request, ResetPassword $resetPassword)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ResetPassword  $resetPassword
     * @return \Illuminate\Http\Response
     */
    public function destroy(ResetPassword $resetPassword)
    {
        //
    }
}
