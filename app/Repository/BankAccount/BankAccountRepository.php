<?php

namespace App\Repository\BankAccount;

use App\Http\Resources\BankAccountResource;
use App\Models\BankAccount;
use App\Repository\BankAccount\BankAccountRepositoryInterface;
use App\Traits\ServiceResponseHandler;

class BankAccountRepository implements BankAccountRepositoryInterface
{
    use ServiceResponseHandler;

    public function get(): object
    {
        return $this->successResponse('fetch bank account success', BankAccountResource::collection(BankAccount::all()));
    }

    public function create($request)
    {
        $logo = \IconUploader($request->file('logo'), 'bank');
        
        $_req = $request->all();
        $_req['logo_bank'] = $logo;

        return $this->successResponse('store bank account success', new BankAccountResource(BankAccount::create($_req)));
    }
}
