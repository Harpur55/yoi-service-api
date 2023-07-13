<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSellerEmailNotificationSettingRequest;
use App\Http\Requests\UpdateSellerEmailNotificationSettingRequest;
use App\Repository\SellerEmailNotificationSetting\SellerEmailNotificationSettingRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class SellerEmailNotificationSettingController extends Controller
{
    protected $sellerEmailNotificationSettingRepository;

    public function __construct(SellerEmailNotificationSettingRepositoryInterface $sellerEmailNotificationSettingRepository)
    {
        $this->sellerEmailNotificationSettingRepository = $sellerEmailNotificationSettingRepository;
    }

    public function show()
    {
        try {
            $response = $this->sellerEmailNotificationSettingRepository->fetch();

            return $this->successResponse($response->message, $response->data);
        } catch(Exception $e) {
            return $this->serverErrorResponse($e->getMessage());
        }
    }

    public function update(UpdateSellerEmailNotificationSettingRequest $request)
    {
        try {
            DB::beginTransaction();

            $response = $this->sellerEmailNotificationSettingRepository->update($request);
            
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
