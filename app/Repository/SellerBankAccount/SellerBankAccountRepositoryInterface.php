<?php

namespace App\Repository\SellerBankAccount;

interface SellerBankAccountRepositoryInterface
{
    public function getAll($request);
    public function getById($id);
    public function create($request);
    public function changeStatus($request);
}
