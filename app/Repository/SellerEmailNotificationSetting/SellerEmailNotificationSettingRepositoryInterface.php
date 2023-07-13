<?php

namespace App\Repository\SellerEmailNotificationSetting;

interface SellerEmailNotificationSettingRepositoryInterface
{
    public function fetch();
    public function update($request);
}
