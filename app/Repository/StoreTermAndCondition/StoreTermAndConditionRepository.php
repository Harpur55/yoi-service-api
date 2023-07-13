<?php

namespace App\Repository\StoreTermAndCondition;

use App\Models\Seller;
use App\Models\Store;
use App\Models\StoreTermAndCondition;
use App\Traits\ServiceResponseHandler;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class StoreTermAndConditionRepository implements StoreTermAndConditionRepositoryInterface
{
    use ServiceResponseHandler;

    public function fetchBySeller()
    {
        $seller = Seller::where('user_id', Auth::id())->first();
        $store = Store::where('seller_id', $seller->id)->first();
        
        return $this->successResponse('fetch tac success', $store->tac);
    }

    public function create($request)
    {
        $validated_request = $request->validated();

        $seller = Seller::where('user_id', Auth::id())->first();
        $store = Store::where('seller_id', $seller->id)->first();

        // $exists = StoreTermAndCondition::where('receiver_name', $validated_request['receiver_name'])
        //                                         ->where('receiver_phone', $validated_request['receiver_phone'])
        //                                         ->where('city', $validated_request['city'])
        //                                         ->where('district', $validated_request['district'])
        //                                         ->where('full_address', $validated_request['full_address'])
        //                                         ->first();
        // if ($exists) {
        //     return $this->errorResponse('duplicate data', null);
        // }

        $validated_request['store_id'] = $store->id;
        $validated_request['opening_time_operational'] = Carbon::parse($validated_request['opening_time_operational']);
        $validated_request['closing_time_operational'] = Carbon::parse($validated_request['closing_time_operational']);
        $validated_request['opening_time_operational_description'] = $request->opening_time_operational_description == "" ?? "";
        $validated_request['closing_time_operational_description'] = $request->closing_time_operational_descriptions == "" ?? "";

        $tac = StoreTermAndCondition::create($validated_request);

        return $this->successResponse('create tac success', $tac);
    }
}
