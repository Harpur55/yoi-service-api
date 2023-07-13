<?php

namespace App\Repository\ProductDetail;

use App\Http\Resources\ProductDetailResource;
use App\Models\ProductDetail;
use App\Traits\ServiceResponseHandler;

class ProductDetailRepository implements ProductDetailRepositoryInterface
{
    use ServiceResponseHandler;

    public function getAll($request): object
    {
        return $this->successResponse('Successfully fetch product details', ProductDetail::all());
    }

    public function getById($id): object
    {
        $product_detail = ProductDetail::find($id);

        if (isset($product_detail)) {
            return $this->successResponse('Successfully fetch product detail', $product_detail);
        } else {
            return $this->errorResponse('Product Detail not found', null);
        }
    }

    public function create($request): object
    {
        $product_detail = ProductDetail::create($request);

        return $this->successResponse('Successfully create product detail', new ProductDetailResource($product_detail));
    }
}
