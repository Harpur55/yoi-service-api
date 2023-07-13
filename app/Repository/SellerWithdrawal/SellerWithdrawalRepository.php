<?php

namespace App\Repository\SellerWithdrawal;

use App\Traits\ServiceResponseHandler;

class SellerWithdrawalRepository
{
    use ServiceResponseHandler;

    public function getAll($request): object
    {
        return $this->successResponse('Successfully fetch products', Product::all());
    }

    public function getById($id): object
    {
        $product = Product::find($id);

        if (isset($product)) {
            return $this->successResponse('Successfully fetch product', $product);
        } else {
            return $this->errorResponse('Product not found', null);
        }
    }

    public function create($request): object
    {
        $validated_request = $request->validated();

        $store = Store::find($validated_request['store_id']);

        if (isset($store)) {
            $product_category = ProductCategory::find($validated_request['product_category_id']);

            if (isset($product_category)) {
                $product = Product::create($validated_request);

                return $this->successResponse('Successfully create product', $product);
            } else {
                return $this->errorResponse('Product Category not found', null);
            }
        } else {
            return $this->errorResponse('Store not found', null);
        }
    }
}
