<?php

namespace App\Repository\SellerWithdrawal;

interface SellerWithdrawalRepositoryInterface
{
    public function getAll($request);
    public function getById($id);
    public function create($request);
}
