<?php

namespace App\Repository\BankAccount;

interface BankAccountRepositoryInterface
{
    public function get();
    public function create($request);
}
