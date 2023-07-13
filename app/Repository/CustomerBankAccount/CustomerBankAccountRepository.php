<?php

namespace App\Repository\CustomerBankAccount;

use App\Http\Resources\CustomerBankAccountResource;
use App\Models\BankAccount;
use App\Models\Customer;
use App\Models\CustomerBankAccount;
use App\Traits\ServiceResponseHandler;
use Illuminate\Support\Facades\Auth;

class CustomerBankAccountRepository implements CustomerBankAccountRepositoryInterface
{
    use ServiceResponseHandler;

    public function getByCustomer(): object
    {
        $customer = Customer::where('user_id', Auth::id())->first();

        $accounts = CustomerBankAccount::where('customer_id', $customer->id)->orderBy('is_active', 'DESC')->get();

        return $this->successResponse('fetch bank account success', CustomerBankAccountResource::collection($accounts));
    }

    public function create($request): object
    {
        $validated_request = $request->validated();

        $customer = Customer::where('user_id', Auth::id())->first();

        $valid_bank = BankAccount::where('provider_name', $validated_request['account_provider'])->first();

        if (!$valid_bank) {
            return $this->errorResponse('invalid bank', null);
        }

        $exist = CustomerBankAccount::where('customer_id', $customer->id)
                                    ->where('account_number', $validated_request['account_number'])
                                    ->where('account_name', $validated_request['account_name'])
                                    ->where('account_provider', $valid_bank->provider_code)
                                    ->first();
        
        if ($exist) {
            return $this->errorResponse('bank account already exist', null);
        }

        $curr_active = CustomerBankAccount::where('customer_id', $customer->id)->where('is_active', TRUE)->first();
        
        if (!$curr_active) {
            $validated_request['is_active'] = 1;
        }

        $validated_request['customer_id'] = $customer->id;
        $validated_request['account_provider'] = $valid_bank->provider_code;
        $validated_request['provider_name'] = $valid_bank->provider_name;
        $validated_request['logo_bank'] = $valid_bank->logo_bank;

        $account = CustomerBankAccount::create($validated_request);

        return $this->successResponse('store bank account success', new CustomerBankAccountResource($account));
    }

    public function changeStatus($id)
    {
        $customer = Customer::where('user_id', Auth::id())->first();
        $account = CustomerBankAccount::where('id', $id)->where('customer_id', $customer->id)->first();

        if ($account) {
            $curr_active = CustomerBankAccount::where('customer_id', $customer->id)->where('is_active', TRUE)->first();

            if ($curr_active && $curr_active->id != $id) {
                $curr_active->is_active = !$curr_active->is_active;
                $curr_active->save();

                $account->is_active = !$account->is_active;
                $account->save();
        
                return $this->successResponse('change status account success', $account);
            }

            return $this->errorResponse('cannot inactivate this account, because there is no active account', null);
        } else {
            return $this->errorResponse('account not found', null);
        }
    }
}
