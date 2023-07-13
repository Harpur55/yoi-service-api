<?php

namespace App\Repository\OrderPayment;

interface OrderPaymentRepositoryInterface
{
    public function create($request, $order);
    public function afterPayment($request);
}
