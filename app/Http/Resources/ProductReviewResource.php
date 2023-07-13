<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'            => $this['id'],
            'review'        => $this['review'],
            'rate'          => $this['rate'],
            'product_rate'  => new ProductRateResource($this['productRate']),
            'customer'      => new CustomerResource($this['customer']),
            'created_at'    => Carbon::parse($this['created_at'])->toDateTimeString(),
            'updated_at'    => Carbon::parse($this['updated_at'])->toDateTimeString()
        ];
    }
}
