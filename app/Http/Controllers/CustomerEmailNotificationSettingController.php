<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCustomerEmailNotificationSettingRequest;
use App\Repository\CustomerEmailNotificationSetting\CustomerEmailNotificationSettingRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class CustomerEmailNotificationSettingController extends Controller
{
    protected $customerEmailNotificationSettingRepository;

    public function __construct(CustomerEmailNotificationSettingRepositoryInterface $customerEmailNotificationSettingRepository)
    {
        $this->customerEmailNotificationSettingRepository = $customerEmailNotificationSettingRepository;
    }

    public function show()
    {
        try {
            $response = $this->customerEmailNotificationSettingRepository->fetch();

            return $this->successResponse($response->message, $response->data);
        } catch(Exception $e) {
            return $this->serverErrorResponse($e->getMessage());
        }
    }

    public function update(UpdateCustomerEmailNotificationSettingRequest $request)
    {
        try {
            DB::beginTransaction();

            $response = $this->customerEmailNotificationSettingRepository->update($request);
            
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
