<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'    => $this['id'],
            'order_id'  => $this['order_id'],
            'product'   => new ProductResource($this['product']),
            'quantity'  => $this['quantity'],
            'price'  => $this['price'],
            'total_price'  => $this['total_price'],
            'discount'  => $this['discount'],
            'created_at'                => Carbon::parse($this['created_at'])->toDateTimeString(),
            'updated_at'                => Carbon::parse($this['updated_at'])->toDateTimeString()
        ];
    }
}
