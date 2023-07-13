<?php

namespace App\Repository\Seller;

interface SellerRepositoryInterface
{
    public function getAll($request);
    public function getById();
    public function create($request);
    public function updateProfile($request);
    public function showDetailRateProduct($id);
}
