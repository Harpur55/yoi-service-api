<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'seller'        => new SellerResource($this['seller']),
            'customer'      => new CustomerResource($this['customer']),
            'detail'        => new OrderDetailResource($this['detail']),
            'payment'       => $this['payment'],
            'shipping'      => $this['shipping'],
            'receipt_number' => $this['receipt_number'],
            'marchant_id'   => $this['merchant_id'],
            'created_at'    => Carbon::parse($this['created_at'])->toDateTimeString(),
            'updated_at'    => Carbon::parse($this['updated_at'])->toDateTimeString()
        ];
    }
}
