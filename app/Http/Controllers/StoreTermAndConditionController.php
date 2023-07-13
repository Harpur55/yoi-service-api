<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStoreTermAndConditionRequest;
use App\Http\Requests\UpdateStoreTermAndConditionRequest;
use App\Models\StoreTermAndCondition;
use App\Repository\StoreTermAndCondition\StoreTermAndConditionRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class StoreTermAndConditionController extends Controller
{
    protected $storeTermAndConditionRepository;

    public function __construct(StoreTermAndConditionRepositoryInterface $storeTermAndConditionRepository)
    {
        $this->storeTermAndConditionRepository = $storeTermAndConditionRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $response = $this->storeTermAndConditionRepository->fetchBySeller();

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
     * @param  \App\Http\Requests\StoreStoreTermAndConditionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStoreTermAndConditionRequest $request)
    {
        try {
            DB::beginTransaction();
            
            $response = $this->storeTermAndConditionRepository->create($request);

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
     * @param  \App\Models\StoreTermAndCondition  $storeTermAndCondition
     * @return \Illuminate\Http\Response
     */
    public function show(StoreTermAndCondition $storeTermAndCondition)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StoreTermAndCondition  $storeTermAndCondition
     * @return \Illuminate\Http\Response
     */
    public function edit(StoreTermAndCondition $storeTermAndCondition)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateStoreTermAndConditionRequest  $request
     * @param  \App\Models\StoreTermAndCondition  $storeTermAndCondition
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStoreTermAndConditionRequest $request, StoreTermAndCondition $storeTermAndCondition)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StoreTermAndCondition  $storeTermAndCondition
     * @return \Illuminate\Http\Response
     */
    public function destroy(StoreTermAndCondition $storeTermAndCondition)
    {
        //
    }
}
