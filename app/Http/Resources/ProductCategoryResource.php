<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductCategoryResource extends JsonResource
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
            'name'          => $this['name'],
            'icon_path'     => url(config('assets.category_icon_location') . $this['icon_path']),
            'detail_icon'   => url(config('assets.category_icon_location') . $this['detail_icon']),
            'created_at'    => Carbon::parse($this['created_at'])->toDateTimeString(),
            'updated_at'    => Carbon::parse($this['updated_at'])->toDateTimeString()
        ];
    }
}
