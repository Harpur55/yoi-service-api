<?php

namespace App\Repository\SellerApplicationNotificationSetting;

interface SellerApplicationNotificationSettingRepositoryInterface
{
    public function fetch();
    public function update($request);
}
