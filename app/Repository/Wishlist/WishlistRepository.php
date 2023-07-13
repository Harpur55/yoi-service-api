<?php

namespace App\Repository\Wishlist;

use App\Http\Resources\WishlistResource;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Wishlist;
use App\Models\WishlistDetail;
use App\Traits\ServiceResponseHandler;
use Illuminate\Support\Facades\Auth;

class WishlistRepository implements WishlistRepositoryInterface
{
    use ServiceResponseHandler;

    public function create($request)
    {
        $validated_request = $request->validated();

        if ($validated_request['wishlist_status'] == "increase" || $validated_request['wishlist_status'] == "decrease") {
            if (isset($validated_request['wishlist_id'])) {
                $wishlist = Wishlist::find($validated_request['wishlist_id']);
            
                if ($wishlist) {
                    $detail = WishlistDetail::where('wishlist_id', $wishlist->id)->first();
                    if ($validated_request['wishlist_status'] == "increase") {
                        $detail->amount += 1;
                    } else {
                        if ($detail->amount > 1) {
                            $detail->amount -= 1;
                        } 
                    }
                    $detail->save();

                    return $this->successResponse($validated_request['wishlist_status'] . " wishlist success" , $wishlist);
                } else {
                    return $this->errorResponse('wishlist not found', null);
                }
            }
        }

        if ($validated_request['wishlist_status'] == "new") {
            $product = Product::with('detail')->where('id', $validated_request['product_id'])->first();

            if ($product) {
                $customer = Customer::where('user_id', Auth::id())->first();

                $duplicate_detail_wishlist = WishlistDetail::where('store_name', $product->store->seller->profile->full_name)
                                                    ->where('price', $product->detail->price)
                                                    ->where('selected_size', $validated_request['size'])
                                                    ->where('selected_color', $validated_request['color'])
                                                    ->first();

                if ($duplicate_detail_wishlist) {
                    $duplicate_detail_wishlist->amount += $validated_request['amount'];
                    $duplicate_detail_wishlist->save();

                    return $this->successResponse('increase wishlist success', $duplicate_detail_wishlist->wishlist);
                }

                $request_wishlist = [
                    'product_id'        => $validated_request['product_id'],
                    'customer_id'       => $customer->id
                ];
        
                $wishlist = Wishlist::create($request_wishlist);
        
                $product = Product::with('detail')->where('id', $wishlist->product_id)->first();
        
                $request_wishlist_detail = [
                    'wishlist_id'           => $wishlist->id,
                    'store_name'            => $product->store->seller->profile->full_name,
                    'amount'                => $validated_request['amount'],
                    'price'                 => $product->detail->price,
                    'discount'              => 0.0,
                    'selected_size'         => $validated_request['size'],
                    'selected_color'        => $validated_request['color']
                ];
        
                WishlistDetail::create($request_wishlist_detail);

                return $this->successResponse('create wishlist success', $wishlist);
            } else {
                return $this->errorResponse('product not found', null);
            }
        }

        return $this->errorResponse('invalid wishlist status type', null);
    }

    public function getByCustomerId()
    {
        $keyword = null;

        if (request()->filled('keyword')) {
            $keyword = request()->query('keyword');
        }

        $customer = Customer::where('user_id', Auth::id())->first();
        $wishlist = Wishlist::with('detail')
                        ->whereHas('product.detail', function ($query) use ($keyword) {
                            $query->when($keyword, function ($query_keyword, $keyword) {
                                return $query_keyword->where('name', 'LIKE', '%'.$keyword.'%');
                            });
                        })
                        ->where('customer_id', $customer->id)
                        ->get();

        return $this->successResponse('fetch wishlist success', WishlistResource::collection($wishlist));
    }

    public function delete($id)
    {
        $wishlist = Wishlist::find($id);
        
        if ($wishlist) {
            $wishlist->delete();

            return $this->successResponse('delete wishlist success', $wishlist);
        } else {
            return $this->errorResponse('wishlist not found', null);
        }
    }
}
