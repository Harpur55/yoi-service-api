<?php

namespace App\Repository\OrderDetail;

interface OrderDetailRepositoryInterface
{
    public function getAll($request);
    public function getById($id);
    public function create($request, $order, $product);
}
