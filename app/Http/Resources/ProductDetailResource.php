<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class ProductDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        App::environment();

        return [
            'id'                        => $this['id'],
            'name'                      => $this['name'],
            'image'                     => url(config('assets.product_image_location') . $this['image']),
            'price'                     => $this['price'],
            'discount'                  => $this['discount'],
            'keyword'                   => $this['keyword'],
            'description'               => $this['description'],
            'sku'                       => $this['sku'],
            'stock_status'              => $this['stock_status'],
            'selected_service'          => $this['selected_service'],
            'selected_size'             => $this['selected_size'],
            'selected_color'            => $this['selected_color'],
            'weight'                    => $this['weight'],
            'long'                      => $this['long'],
            'wide'                      => $this['wide'],
            'tall'                      => $this['tall'],
            'visibility'                => $this['visibility'],
            'additional_note'           => $this['additional_note'],
            'show_additional_note'      => $this['show_additional_note'],
            'created_at'                => Carbon::parse($this['created_at'])->toDateTimeString(),
            'updated_at'                => Carbon::parse($this['updated_at'])->toDateTimeString()
        ];
    }
}
