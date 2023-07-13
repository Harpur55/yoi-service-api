<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSellerApplicationNotificationSettingRequest;
use App\Repository\SellerApplicationNotificationSetting\SellerApplicationNotificationSettingRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class SellerApplicationNotificationSettingController extends Controller
{
    protected $sellerApplicationNotificationSettingRepository;

    public function __construct(SellerApplicationNotificationSettingRepositoryInterface $sellerApplicationNotificationSettingRepository)
    {
        $this->sellerApplicationNotificationSettingRepository = $sellerApplicationNotificationSettingRepository;
    }

    public function show()
    {
        try {
            $response = $this->sellerApplicationNotificationSettingRepository->fetch();

            return $this->successResponse($response->message, $response->data);
        } catch(Exception $e) {
            return $this->serverErrorResponse($e->getMessage());
        }
    }

    public function update(UpdateSellerApplicationNotificationSettingRequest $request)
    {
        try {
            DB::beginTransaction();

            $response = $this->sellerApplicationNotificationSettingRepository->update($request);
            
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
