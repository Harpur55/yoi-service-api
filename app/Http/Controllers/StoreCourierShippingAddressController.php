<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStoreCourierShippingAddressRequest;
use App\Http\Requests\UpdateStoreCourierShippingAddressRequest;
use App\Models\StoreCourierShippingAddress;
use App\Repository\StoreCourierShippingAddress\StoreCourierShippingAddressRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class StoreCourierShippingAddressController extends Controller
{
    protected $storeCourierShippingAddress;

    public function __construct(StoreCourierShippingAddressRepositoryInterface $storeCourierShippingAddress)
    {
        $this->storeCourierShippingAddress = $storeCourierShippingAddress;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $response = $this->storeCourierShippingAddress->fetchBySeller();

            if ($response->status) {
                return $this->successResponse($response->message, $response->data);
            } else {
                return $this->errorResponse($response->message, $response->data);
            }
        } catch (Exception $e) {
            return $this->serverErrorResponse($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreStoreCourierShippingAddressRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStoreCourierShippingAddressRequest $request)
    {
        try {
            DB::beginTransaction();
            
            $response = $this->storeCourierShippingAddress->create($request);

            if ($response->status) {
                DB::commit();

                return $this->successResponse($response->message, $response->data);
            } else {
                DB::rollBack();

                return $this->errorResponse($response->message, $response->data);
            }
        } catch (Exception $e) {
            DB::rollBack();

            return $this->serverErrorResponse($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StoreCourierShippingAddress  $storeCourierShippingAddress
     * @return \Illuminate\Http\Response
     */
    public function show(StoreCourierShippingAddress $storeCourierShippingAddress)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StoreCourierShippingAddress  $storeCourierShippingAddress
     * @return \Illuminate\Http\Response
     */
    public function edit(StoreCourierShippingAddress $storeCourierShippingAddress)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateStoreCourierShippingAddressRequest  $request
     * @param  \App\Models\StoreCourierShippingAddress  $storeCourierShippingAddress
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStoreCourierShippingAddressRequest $request, StoreCourierShippingAddress $storeCourierShippingAddress)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StoreCourierShippingAddress  $storeCourierShippingAddress
     * @return \Illuminate\Http\Response
     */
    public function destroy(StoreCourierShippingAddress $storeCourierShippingAddress)
    {
        //
    }

    public function changeStatus($id)
    {
        try {
            DB::beginTransaction();

            $response = $this->storeCourierShippingAddress->changeStatus($id);

            if ($response->status) {
                DB::commit();

                return $this->successResponse($response->message, $response->data);
            } else {
                DB::rollBack();

                return $this->errorResponse($response->message, $response->data);
            }
        } catch (Exception $exception) {
            DB::rollBack();
            
            return $this->serverErrorResponse($exception->getMessage());
        }
    }
}
