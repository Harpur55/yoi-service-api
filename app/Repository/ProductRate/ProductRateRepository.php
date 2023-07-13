<?php

namespace App\Repository\ProductRate;

use App\Http\Resources\ProductRateResource;
use App\Models\ProductRate;
use App\Traits\ServiceResponseHandler;

class ProductRateRepository implements ProductRateRepositoryInterface
{
    use ServiceResponseHandler;

    public function getAll($request): object
    {
        return $this->successResponse('Successfully fetch product rates', ProductRateResource::collection(ProductRate::all()));
    }

    public function getById($id): object
    {
        $product_rate = ProductRate::find($id);

        if (isset($product_rate)) {
            return $this->successResponse('Successfully fetch product rate', new ProductRateResource($product_rate));
        } else {
            return $this->errorResponse('Product rate not found', null);
        }
    }

    public function create($request): object
    {
        $product_rate = ProductRate::create($request);

        return $this->successResponse('Successfully create product rate', new ProductRateResource($product_rate));
    }
}
