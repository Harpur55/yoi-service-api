<?php

namespace App\Repository\SellerApplicationNotificationSetting;

use App\Models\Seller;
use App\Models\SellerApplicationNotificationSetting;
use App\Traits\ServiceResponseHandler;
use Illuminate\Support\Facades\Auth;

class SellerApplicationNotificationSettingRepository implements SellerApplicationNotificationSettingRepositoryInterface
{
    use ServiceResponseHandler;

    public function fetch()
    {
        $seller = Seller::where('user_id', Auth::id())->first();

        return $this->successResponse('fetch app notif success', $seller->appNotif);
    }

    public function update($request)
    {
        $validated_request = $request->validated();

        $seller = Seller::where('user_id', Auth::id())->first();
        $emailNotif = SellerApplicationNotificationSetting::where('seller_id', $seller->id)->first();
        $emailNotif->enable_all = $validated_request['enable_all'];
        $emailNotif->new_order = $validated_request['new_order'];
        $emailNotif->in_progress_order = $validated_request['in_progress_order'];
        $emailNotif->reject_order = $validated_request['reject_order'];
        $emailNotif->finish_order = $validated_request['finish_order'];
        $emailNotif->success_withdraw = $validated_request['success_withdraw'];
        $emailNotif->fail_withdraw = $validated_request['fail_withdraw'];
        $emailNotif->promotion = $validated_request['promotion'];
        $emailNotif->save();

        return $this->successResponse('update app notif success', $emailNotif);
    }
}
