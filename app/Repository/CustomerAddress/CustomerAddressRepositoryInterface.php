<?php

namespace App\Repository\CustomerAddress;

interface CustomerAddressRepositoryInterface
{
    public function create($request);
    public function fetch();
    public function changeStatus($id);
}
