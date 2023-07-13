<?php

namespace App\Repository\CustomerProfile;

use App\Models\Customer;
use App\Models\CustomerProfile;
use App\Traits\ServiceResponseHandler;

class CustomerProfileRepository implements CustomerProfileRepositoryInterface
{
    use ServiceResponseHandler;

    public function getAll($request): object
    {
        return $this->successResponse('Successfully fetch customer profiles', CustomerProfile::all());
    }

    public function getById($id): object
    {
        $customer_profile = CustomerProfile::find($id);

        if (isset($customer_profile)) {
            return $this->successResponse('Successfully fetch customer profile detail', $customer_profile);
        } else {
            return $this->errorResponse('Customer Profile not found', null);
        }
    }

    public function create($request): object
    {
        $validated_request = $request->validated();

        $customer = Customer::find($validated_request['customer_id']);

        if (isset($customer)) {
            $customer_profile = CustomerProfile::create($validated_request);

            return $this->successResponse('Successfully create customer profile', $customer_profile);
        } else {
            return $this->errorResponse('Customer not found', null);
        }
    }

    public function update($request, $id): object
    {
        $exist_customer_profile = CustomerProfile::find($id);

        if (isset($exist_customer_profile)) {
            $validated_request = $request->validated();

            $customer_profile = CustomerProfile::create($validated_request);

            return $this->successResponse('Successfully update customer profile', $customer_profile);
        } else {
            return $this->errorResponse('Customer profile not found', null);
        }
    }

    public function delete($id): object
    {
        $customer_profile = CustomerProfile::find($id);

        if (isset($customer_profile)) {
            $customer_profile->delete();

            return $this->successResponse('Successfully delete customer profile', null);
        } else {
            return $this->errorResponse('Customer Profile not found', null);
        }
    }
}
