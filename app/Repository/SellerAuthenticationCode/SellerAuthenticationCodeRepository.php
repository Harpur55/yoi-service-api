<?php

namespace App\Repository\SellerAuthenticationCode;

use App\Models\Seller;
use App\Models\SellerAuthenticationCode;
use App\Traits\ServiceResponseHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class SellerAuthenticationCodeRepository implements SellerAuthenticationCodeRepositoryInterface
{
    use ServiceResponseHandler;

    public function create($request)
    {
        $validated_request = $request->validated();
        
        $seller = Seller::where('user_id', Auth::id())->first();
        if ($seller->code) {
            return $this->errorResponse('already exist', null);
        }

        $validated_request['seller_id'] = $seller->id;
        $validated_request['code'] = Crypt::encryptString($validated_request['code']);

        return $this->successResponse('create code success', SellerAuthenticationCode::create($validated_request));
    }

    public function validate($request)
    {
        $validated_request = $request->validated();
        
        $seller = Seller::where('user_id', Auth::id())->first();
        if (Crypt::decryptString($seller->code->code) == $validated_request['code']) {
            return $this->successResponse('code validated', null);
        }

        return $this->errorResponse('invalid code', null);
    }

    public function update($request)
    {
        $validated_request = $request->validated();
        
        $seller = Seller::where('user_id', Auth::id())->first();
        if ($seller->code) {
            $code = SellerAuthenticationCode::where('seller_id', $seller->id)->first();
            $code->code = Crypt::encryptString($validated_request['code']);
            $code->save();

            return $this->successResponse('update code success', $code);
        }

        return $this->errorResponse('doesnt have auth code', null);
    }

    public function fetchBySeller()
    {
        $seller = Seller::where('user_id', Auth::id())->first();

        return $this->successResponse('fetch code success', $seller->code);
    }
}
