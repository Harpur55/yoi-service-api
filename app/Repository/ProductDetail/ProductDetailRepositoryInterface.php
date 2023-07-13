<?php

namespace App\Repository\ProductDetail;

interface ProductDetailRepositoryInterface
{
    public function getAll($request);
    public function getById($id);
    public function create($request);
}
