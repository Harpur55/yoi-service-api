<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
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
            'id'                        => $this['id'],
            'image'                     => url(config('assets.banner_image_location') . $this['image']),
            'created_at'                => Carbon::parse($this['created_at'])->toDateTimeString(),
            'updated_at'                => Carbon::parse($this['updated_at'])->toDateTimeString()
        ];
    }
}
