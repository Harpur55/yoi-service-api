<?php

namespace App\Repository\StoreTermAndCondition;

interface StoreTermAndConditionRepositoryInterface
{
    public function fetchBySeller();
    public function create($request);
}
