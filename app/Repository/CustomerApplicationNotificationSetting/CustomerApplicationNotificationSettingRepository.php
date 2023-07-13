<?php

namespace App\Repository\CustomerApplicationNotificationSetting;

use App\Models\Customer;
use App\Models\CustomerApplicationNotificationSetting;
use App\Traits\ServiceResponseHandler;
use Illuminate\Support\Facades\Auth;

class CustomerApplicationNotificationSettingRepository implements CustomerApplicationNotificationSettingRepositoryInterface
{
    use ServiceResponseHandler;

    public function fetch()
    {
        $customer = Customer::where('user_id', Auth::id())->first();

        return $this->successResponse('fetch app notif success', $customer->appNotif);
    }

    public function update($request)
    {
        $validated_request = $request->validated();

        $customer = Customer::where('user_id', Auth::id())->first();
        $appNotif = CustomerApplicationNotificationSetting::where('customer_id', $customer->id)->first();
        $appNotif->enable_all = $validated_request['enable_all'];
        $appNotif->order_status = $validated_request['order_status'];
        $appNotif->chat = $validated_request['chat'];
        $appNotif->promotion = $validated_request['promotion'];
        $appNotif->save();

        return $this->successResponse('update app notif success', $appNotif);
    }
}
