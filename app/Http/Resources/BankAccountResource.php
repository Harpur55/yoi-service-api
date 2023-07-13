<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BankAccountResource extends JsonResource
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
            'id'                => $this['id'],
            'provider_code'     => $this['provider_code'],
            'provider_name'     => $this['provider_name'],
            'logo'              => url(config('assets.bank_icon_location') . $this['logo_bank']),
            'created_at'        => $this['created_at'],
            'updated_at'        => $this['updated_at']
        ];
    }
}
