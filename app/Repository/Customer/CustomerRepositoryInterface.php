<?php

namespace App\Repository\Customer;

interface CustomerRepositoryInterface
{
    public function getAll($request);
    public function getById($id);
    public function create($request);
}
