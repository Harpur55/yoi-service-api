<?php

namespace App\Repository\SellerProfile;

use App\Models\SellerProfile;
use App\Traits\ServiceResponseHandler;

class SellerProfileRepository implements SellerProfileRepositoryInterface
{
    use ServiceResponseHandler;

    public function create($request)
    {
        return $this->successResponse('create profile success', SellerProfile::create($request));
    }
}
