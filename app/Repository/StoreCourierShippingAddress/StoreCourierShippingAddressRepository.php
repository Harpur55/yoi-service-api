<?php

namespace App\Repository\StoreCourierShippingAddress;

use App\Models\Seller;
use App\Models\Store;
use App\Models\StoreCourierShippingAddress;
use App\Models\User;
use App\Traits\ServiceResponseHandler;
use Illuminate\Support\Facades\Auth;

class StoreCourierShippingAddressRepository implements StoreCourierShippingAddressRepositoryInterface
{
    use ServiceResponseHandler;

    public function fetchBySeller()
    {
        $seller = Seller::where('user_id', Auth::id())->first();
        $store = Store::where('seller_id', $seller->id)->first();
        $address = StoreCourierShippingAddress::where('store_id', $store->id)->orderBy('is_active', 'desc')->get();
        
        return $this->successResponse('fetch shipping courier success', $address);
    }

    public function create($request)
    {
        $validated_request = $request->validated();

        $seller = Seller::where('user_id', Auth::id())->first();
        $store = Store::where('seller_id', $seller->id)->first();

        $exists = StoreCourierShippingAddress::where('receiver_name', $validated_request['receiver_name'])
                                                ->where('receiver_phone', $validated_request['receiver_phone'])
                                                ->where('city', $validated_request['city'])
                                                ->where('district', $validated_request['district'])
                                                ->where('full_address', $validated_request['full_address'])
                                                ->first();
        if ($exists) {
            return $this->errorResponse('duplicate data', null);
        }

        $validated_request['store_id'] = $store->id;
        $validated_request['latitude'] = 0;
        $validated_request['longitude'] = 0;
        $validated_request['prov_id'] = $validated_request['prov_id'];
        $validated_request['prov_name'] = $validated_request['prov_name'];
        $validated_request['city_id'] = $validated_request['city_id'];
        $validated_request['city_name'] = $validated_request['city_name'];
        $validated_request['district_id'] = $validated_request['district_id'];
        $validated_request['district_name'] = $validated_request['district_name'];
        $validated_request['subdistrict_id'] = $validated_request['subdistrict_id'];
        $validated_request['subdistrict_name'] = $validated_request['subdistrict_name'];

        $current_active = StoreCourierShippingAddress::where('is_active', true)->where('store_id', $store->id)->first();
        if (!isset($current_active)) {
            $validated_request['is_active'] = true;
        }

        $courier_shipping = StoreCourierShippingAddress::create($validated_request);

        return $this->successResponse('create shipping courier success', $courier_shipping);
    }

    public function changeStatus($id)
    {
        $seller = Seller::where('user_id', Auth::id())->first();
        $store = Store::where('seller_id', $seller->id)->first();
        $address = StoreCourierShippingAddress::where('id', $id)->where('store_id', $store->id)->first();
       
        if ($address) {
            $current_active = StoreCourierShippingAddress::where('is_active', true)->where('store_id', $store->id)->first();
            
            if ($current_active && $current_active->id != $id) {
                $current_active->is_active = !$current_active->is_active;
                $current_active->save();

                $address->is_active = !$address->is_active;
                $address->save();
        
                return $this->successResponse('change status address success', $address);
            } else {
                $address->is_active = !$address->is_active;
                $address->save();
        
                return $this->successResponse('change status address success', $address);
            }
        } else {
            return $this->errorResponse('address not found', null);
        }
    }
}
