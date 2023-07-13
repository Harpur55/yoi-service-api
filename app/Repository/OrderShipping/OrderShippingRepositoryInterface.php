<?php

namespace App\Repository\OrderShipping;

interface OrderShippingRepositoryInterface {
    public function create($request, $order);
}