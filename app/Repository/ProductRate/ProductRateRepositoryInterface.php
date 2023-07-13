<?php

namespace App\Repository\ProductRate;

interface ProductRateRepositoryInterface
{
    public function getAll($request);
    public function getById($id);
    public function create($request);
}
