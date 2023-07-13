<?php

namespace App\Repository\Customer;

use App\Models\Customer;
use App\Models\User;
use App\Traits\ServiceResponseHandler;

class CustomerRepository implements CustomerRepositoryInterface
{
    use ServiceResponseHandler;

    public function getAll($request): object
    {
        return $this->successResponse('Successfully fetch customers', Customer::all());
    }

    public function getById($id): object
    {
        $customer = Customer::find($id);

        if (isset($customer)) {
            return $this->successResponse('Successfully fetch customer detail', $customer);
        } else {
            return $this->errorResponse('Customer not found', null);
        }
    }

    public function create($request): object
    {
        $validated_request = $request->validated();

        $user = User::find($validated_request['user_id']);

        if (isset($user)) {
            $customer = Customer::create($validated_request);

            return $this->successResponse('Create customer success', $customer);
        } else {
            return $this->errorResponse('User not found', null);
        }
    }
}
