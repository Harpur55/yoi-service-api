<?php

namespace App\Repository\Store;

use App\Models\Store;
use App\Traits\ServiceResponseHandler;

class StoreRepository implements StoreRepositoryInterface
{
    use ServiceResponseHandler;

    public function create($request): object
    {
        $store = Store::create($request);

        return $this->successResponse('create store success', $store);
    }
}
