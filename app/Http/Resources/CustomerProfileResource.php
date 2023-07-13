<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerProfileResource extends JsonResource
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
            'first_name'    => $this['first_name'],
            'last_name'     => $this['last_name'],
            'photo_path'    => $this['photo_path'] == "" ? "" : url(config('assets.profile_image_location') . $this['photo_path']),
            'created_at'    => Carbon::parse($this['created_at'])->toDateTimeString(),
            'updated_at'    => Carbon::parse($this['updated_at'])->toDateTimeString()
        ];
    }
}
