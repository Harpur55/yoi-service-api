<?php

namespace App\Repository\SellerBankAccount;

use App\Models\BankAccount;
use App\Models\Seller;
use App\Models\SellerBankAccount;
use App\Traits\ServiceResponseHandler;
use Illuminate\Support\Facades\Auth;

class SellerBankAccountRepository implements SellerBankAccountRepositoryInterface
{
    use ServiceResponseHandler;

    public function getAll($request): object
    {
        $seller = Seller::where('user_id', Auth::id())->first();

        return $this->successResponse('Successfully fetch seller bank accounts', SellerBankAccount::where('seller_id', $seller->id)->get());
    }

    public function getById($id): object
    {
        $seller_bank_account = SellerBankAccount::find($id);

        if (isset($seller_bank_account)) {
            return $this->successResponse('Successfully fetch seller bank account detail', $seller_bank_account);
        } else {
            return $this->errorResponse('Seller Bank Account not found', null);
        }
    }

    public function create($request): object
    {
        $validated_request = $request->validated();

        $seller = Seller::where('user_id', Auth::id())->first();

        if (isset($seller)) {
            $valid_bank = BankAccount::where('provider_name', $validated_request['account_provider'])->first();

        if (!$valid_bank) {
            return $this->errorResponse('invalid bank', null);
        }

        $curr_active = SellerBankAccount::where('seller_id', $seller->id)->where('is_active', TRUE)->first();
        
        if (!$curr_active) {
            $validated_request['is_active'] = 1;
        }
            $validated_request['seller_id'] = $seller->id;
            $validated_request['account_provider'] = $valid_bank->provider_code;
        $validated_request['provider_name'] = $valid_bank->provider_name;
            $seller_bank_account = SellerBankAccount::create($validated_request);

            return $this->successResponse('Successfully create Seller Bank Account', $seller_bank_account);
        } else {
            return $this->errorResponse('Seller not found', null);
        }
    }

    public function changeStatus($id)
    {
        $seller = Seller::where('user_id', Auth::id())->first();
        $account = SellerBankAccount::where('id', $id)->where('seller_id', $seller->id)->first();

        if ($account) {
            $curr_active = SellerBankAccount::where('seller_id', $seller->id)->where('is_active', TRUE)->first();

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
