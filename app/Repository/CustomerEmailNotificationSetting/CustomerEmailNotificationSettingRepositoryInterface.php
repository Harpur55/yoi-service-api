<?php

namespace App\Repository\CustomerEmailNotificationSetting;

interface CustomerEmailNotificationSettingRepositoryInterface
{
    public function fetch();
    public function update($request);
}
