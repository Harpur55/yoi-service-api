<?php

namespace App\Repository\CustomerAddress;

use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Traits\ServiceResponseHandler;
use Illuminate\Support\Facades\Auth;

class CustomerAddressRepository implements CustomerAddressRepositoryInterface
{
    use ServiceResponseHandler;

    public function create($request)
    {
        $validated_request = $request->validated();
        
        $customer = Customer::where('user_id', Auth::id())->first();
        
        $exist = CustomerAddress::where('customer_id', $customer->id)
                                ->where('receiver_name', $validated_request['receiver_name'])
                                ->where('receiver_phone', $validated_request['receiver_phone'])
                                ->where('city', $validated_request['city'])
                                ->where('district', $validated_request['district'])
                                ->where('full_address', $validated_request['full_address'])
                                ->first();
        if ($exist) {
            return $this->errorResponse('address exist', $exist);
        }
        
        $validated_request['customer_id'] = $customer->id;
        $validated_request['latitude'] = 0;
        $validated_request['longitude'] = 0;

        $current_active = CustomerAddress::where('is_active', true)->where('customer_id', $customer->id)->first();
        if (!isset($current_active)) {
            $validated_request['is_active'] = true;
        }
        
        return $this->successResponse('create address success', CustomerAddress::create($validated_request));
    }

    public function fetch()
    {
        $customer = Customer::where('user_id', Auth::id())->first();
        $customer_addresses = CustomerAddress::where('customer_id', $customer->id)->orderBy('is_active', 'desc')->get();

        return $this->successResponse('fetch address success', $customer_addresses);
    }

    public function changeStatus($id)
    {
        $customer = Customer::where('user_id', Auth::id())->first();
        $customer_address = CustomerAddress::where('id', $id)->where('customer_id', $customer->id)->first();
       
        if ($customer_address) {
            $current_active = CustomerAddress::where('is_active', true)->where('customer_id', $customer->id)->first();
            
            if ($current_active && $current_active->id != $id) {
                $current_active->is_active = !$current_active->is_active;
                $current_active->save();

                $customer_address->is_active = !$customer_address->is_active;
                $customer_address->save();
        
                return $this->successResponse('change status address success', $customer_address);
            } else {
                $customer_address->is_active = !$customer_address->is_active;
                $customer_address->save();

                return $this->successResponse('change status address success', $customer_address);
            }
        } else {
            return $this->errorResponse('address not found', null);
        }
    }
}
