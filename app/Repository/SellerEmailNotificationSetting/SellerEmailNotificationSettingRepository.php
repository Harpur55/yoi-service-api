<?php

namespace App\Repository\SellerEmailNotificationSetting;

use App\Models\Seller;
use App\Models\SellerEmailNotificationSetting;
use App\Traits\ServiceResponseHandler;
use Illuminate\Support\Facades\Auth;

class SellerEmailNotificationSettingRepository implements SellerEmailNotificationSettingRepositoryInterface
{
    use ServiceResponseHandler;

    public function fetch()
    {
        $seller = Seller::where('user_id', Auth::id())->first();

        return $this->successResponse('fetch email notif success', $seller->emailNotif);
    }

    public function update($request)
    {
        $validated_request = $request->validated();

        $seller = Seller::where('user_id', Auth::id())->first();
        $emailNotif = SellerEmailNotificationSetting::where('seller_id', $seller->id)->first();
        $emailNotif->enable_all = $validated_request['enable_all'];
        $emailNotif->order = $validated_request['order'];
        $emailNotif->withdraw = $validated_request['withdraw'];
        $emailNotif->promotion = $validated_request['promotion'];
        $emailNotif->save();

        return $this->successResponse('update email notif success', $emailNotif);
    }
}
