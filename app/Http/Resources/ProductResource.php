<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'category'      => new ProductCategoryResource($this['category']),
            'detail'        => new ProductDetailResource($this['detail']),
            'rate'          => $this['rate'],
            'store'         => new StoreResource($this['store']),
            'likes'         => ProductLikes::collection($this['likes']),
            'created_at'    => Carbon::parse($this['created_at'])->toDateTimeString(),
            'updated_at'    => Carbon::parse($this['updated_at'])->toDateTimeString()
        ];
    }
}
