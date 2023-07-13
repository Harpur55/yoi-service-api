<?php

namespace App\Repository\StoreCourierShippingAddress;

interface StoreCourierShippingAddressRepositoryInterface
{
    public function fetchBySeller();
    public function create($request);
    public function changeStatus($id);
}
