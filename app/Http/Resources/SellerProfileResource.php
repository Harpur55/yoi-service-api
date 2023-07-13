<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SellerProfileResource extends JsonResource
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
            'id'            => $this['id'],
            'seller_id'     => $this['seller_id'],
            'full_name'     => $this['full_name'],
            'full_address'  => $this['full_address'],
            'latitude'      => $this['latitude'],
            'longitude'     => $this['longitude'],
            'phone'         => $this['phone'],
            'slogan'        => $this['slogan'],
            'description'   => $this['description'],
            'photo_path'    => $this['photo_path'] == "" ? "" : url(config('assets.seller_image_location') . $this['photo_path']),
            'store_name'    => $this['store_name']
        ];
    }
}
