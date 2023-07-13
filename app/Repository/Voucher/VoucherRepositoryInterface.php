<?php

namespace App\Repository\Voucher;

interface VoucherRepositoryInterface
{
    public function getAll($request);
    public function getById($id);
    public function create($request);
}
