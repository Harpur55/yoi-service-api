<?php

namespace App\Repository\CustomerBankAccount;

interface CustomerBankAccountRepositoryInterface
{
    public function getByCustomer();
    public function create($request);
    public function changeStatus($id);
}
