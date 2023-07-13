<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerNotificationRequest;
use App\Http\Requests\UpdateCustomerNotificationRequest;
use App\Models\CustomerNotification;
use App\Repository\CustomerNotification\CustomerNotificationRepositoryInterface;

class CustomerNotificationController extends Controller
{
    protected $customerNotificationRepository;

    public function __construct(CustomerNotificationRepositoryInterface $customerNotificationRepository)
    {
        $this->customerNotificationRepository = $customerNotificationRepository;
    }

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
     * @param  \App\Http\Requests\StoreCustomerNotificationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCustomerNotificationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CustomerNotification  $customerNotification
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerNotification $customerNotification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CustomerNotification  $customerNotification
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerNotification $customerNotification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCustomerNotificationRequest  $request
     * @param  \App\Models\CustomerNotification  $customerNotification
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCustomerNotificationRequest $request, CustomerNotification $customerNotification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomerNotification  $customerNotification
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerNotification $customerNotification)
    {
        //
    }
}
