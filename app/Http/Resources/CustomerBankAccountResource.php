<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerBankAccountResource extends JsonResource
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
            'customer_id'       => $this['customer_id'],
            'account_name'      => $this['account_name'],
            'account_number'    => $this['account_number'],
            'account_provider'  => $this['account_provider'],
            'provider_name'     => $this['provider_name'],
            'is_active'         => $this['is_active'],
            'logo'              => url(config('assets.bank_icon_location') . $this['logo_bank']),
            'created_at'        => $this['created_at'],
            'updated_at'        => $this['updated_at']
        ];
    }
}
