<?php

namespace App\Repository\CustomerEmailNotificationSetting;

use App\Models\Customer;
use App\Models\CustomerEmailNotificationSetting;
use App\Traits\ServiceResponseHandler;
use Illuminate\Support\Facades\Auth;

class CustomerEmailNotificationSettingRepository implements CustomerEmailNotificationSettingRepositoryInterface
{
    use ServiceResponseHandler;

    public function fetch()
    {
        $customer = Customer::where('user_id', Auth::id())->first();

        return $this->successResponse('fetch email notif success', $customer->emailNotif);
    }

    public function update($request)
    {
        $validated_request = $request->validated();

        $customer = Customer::where('user_id', Auth::id())->first();
        $emailNotif = CustomerEmailNotificationSetting::where('customer_id', $customer->id)->first();
        $emailNotif->enable_all = $validated_request['enable_all'];
        $emailNotif->order_status = $validated_request['order_status'];
        $emailNotif->seller_information = $validated_request['seller_information'];
        $emailNotif->save();

        return $this->successResponse('update email notif success', $emailNotif);
    }
}
