<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCustomerApplicationNotificationSettingRequest;
use App\Repository\CustomerApplicationNotificationSetting\CustomerApplicationNotificationSettingRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class CustomerApplicationNotificationSettingController extends Controller
{
    protected $applicationNotificationSettingRepository;

    public function __construct(CustomerApplicationNotificationSettingRepositoryInterface $applicationNotificationSettingRepository)
    {
        $this->applicationNotificationSettingRepository = $applicationNotificationSettingRepository;
    }

    public function show()
    {
        try {
            $response = $this->applicationNotificationSettingRepository->fetch();

            return $this->successResponse($response->message, $response->data);
        } catch(Exception $e) {
            return $this->serverErrorResponse($e->getMessage());
        }
    }

    public function update(UpdateCustomerApplicationNotificationSettingRequest $request)
    {
        try {
            DB::beginTransaction();

            $response = $this->applicationNotificationSettingRepository->update($request);
            
            if ($response->status) {
                DB::commit();

                return $this->successResponse($response->message, $response->data);
            } else {
                DB::rollBack();

                return $this->errorResponse($response->message, $response->data);
            }
        } catch(Exception $e) {
            DB::rollBack();
            
            return $this->serverErrorResponse($e->getMessage());
        }
    }
}
