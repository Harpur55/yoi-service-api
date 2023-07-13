<?php

namespace App\Repository\CustomerApplicationNotificationSetting;

interface CustomerApplicationNotificationSettingRepositoryInterface
{
    public function fetch();
    public function update($request);
}
