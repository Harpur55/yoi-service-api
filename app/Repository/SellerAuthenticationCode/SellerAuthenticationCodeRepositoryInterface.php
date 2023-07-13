<?php

namespace App\Repository\SellerAuthenticationCode;

interface SellerAuthenticationCodeRepositoryInterface
{
    public function create($request);
    public function validate($request);
    public function fetchBySeller();
    public function update($request);
}
