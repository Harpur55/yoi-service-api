<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductRateResource extends JsonResource
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
            'product'       => new ProductResource($this['product']),
            'total'         => $this['total'],
            'created_at'    => Carbon::parse($this['created_at'])->toDateTimeString(),
            'updated_at'    => Carbon::parse($this['updated_at'])->toDateTimeString()
        ];
    }
}
