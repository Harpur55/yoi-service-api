<?php

namespace App\Repository\ProductReview;

use App\Http\Resources\ProductReviewResource;
use App\Models\ProductRate;
use App\Models\ProductReview;
use App\Traits\ServiceResponseHandler;
use Illuminate\Support\Facades\Log;

class ProductReviewRepository implements ProductReviewRepositoryInterface
{
    use ServiceResponseHandler;

    public function getAll($request): object
    {
        return $this->successResponse('Successfully fetch product reviews', ProductReviewResource::collection(ProductReview::all()));
    }

    public function getById($id): object
    {
        $product_review = ProductReview::find($id);

        if (isset($product_review)) {
            return $this->successResponse('Successfully fetch product review', new ProductReviewResource($product_review));
        } else {
            return $this->errorResponse('Product review not found', null);
        }
    }

    public function create($request): object
    {
        $validated_request = $request->validated();

        $product_rate = ProductRate::where('product_id', $validated_request['product_id'])->first();

        $validated_request['product_rate_id'] = $product_rate->id;

        $product_review = ProductReview::create($validated_request);

        $rate_counter = ProductReview::where('product_rate_id', $product_rate->id)->sum('rate');
        $review_counter = ProductReview::where('product_rate_id', $product_rate->id)->get();

        $product_rate->total = $rate_counter / (float) $review_counter->count();
        $product_rate->save();

        return $this->successResponse('Successfully create product review', new ProductReviewResource($product_review));
    }
}
