<?php

namespace App\Repository\Cart;

use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Customer;
use App\Models\Product;
use App\Traits\ServiceResponseHandler;
use Illuminate\Support\Facades\Auth;

class CartRepository implements CartRepositoryInterface
{
    use ServiceResponseHandler;

    public function create($request)
    {
        $validated_request = $request->validated();
        
        if ($validated_request['cart_status'] == "increase" || $validated_request['cart_status'] == "decrease") {
            if (isset($validated_request['cart_id'])) {
                $cart = Cart::find($validated_request['cart_id']);
            
                if ($cart) {
                    $detail = CartDetail::where('cart_id', $cart->id)->first();
                    if ($validated_request['cart_status'] == "increase") {
                        $detail->amount += 1;
                    } else {
                        if ($detail->amount > 1) {
                            $detail->amount -= 1;
                        } 
                    }
                    $detail->save();

                    return $this->successResponse($validated_request['cart_status'] . " cart success" , $cart);
                } else {
                    return $this->errorResponse('cart not found', null);
                }
            }
        }

        if ($validated_request['cart_status'] == "new") {
            $product = Product::with('detail')->where('id', $validated_request['product_id'])->first();

            if ($product) {
                $customer = Customer::where('user_id', Auth::id())->first();

                $duplicate_detail_cart = CartDetail::where('store_name', $product->store->seller->profile->full_name)
                                                    ->where('price', $product->detail->price)
                                                    ->where('selected_size', $validated_request['size'])
                                                    ->where('selected_color', $validated_request['color'])
                                                    ->first();

                if ($duplicate_detail_cart) {
                    $duplicate_detail_cart->amount += $validated_request['amount'];
                    $duplicate_detail_cart->save();

                    return $this->successResponse('increase cart success', $duplicate_detail_cart->cart);
                }

                $request_cart = [
                    'product_id'        => $product->id,
                    'customer_id'       => $customer->id
                ];
                $cart = Cart::create($request_cart);

                $request_cart_detail = [
                    'cart_id'               => $cart->id,
                    'store_name'            => $product->store->seller->profile->full_name,
                    'amount'                => $validated_request['amount'],
                    'price'                 => $product->detail->price,
                    'discount'              => 0.0,
                    'selected_size'         => $validated_request['size'],
                    'selected_color'        => $validated_request['color'],
                ];

                CartDetail::create($request_cart_detail);

                return $this->successResponse('create cart success', $cart);
            } else {
                return $this->errorResponse('product not found', null);
            }
        }

        return $this->errorResponse('invalid cart status type', null);
    }

    public function getByCustomerId()
    {
        $keyword = null;

        if (request()->filled('keyword')) {
            $keyword = request()->query('keyword');
        }

        $customer = Customer::where('user_id', Auth::id())->first();
        $carts = Cart::with('detail')
                    ->whereHas('product.detail', function ($query) use ($keyword) {
                        $query->when($keyword, function ($query_keyword, $keyword) {
                            return $query_keyword->where('name', 'LIKE', '%'.$keyword.'%');
                        });
                    })
                    ->orderBy('id', 'desc')
                    ->where('customer_id', $customer->id)
                    ->get();

        return $this->successResponse('fetch cart success', CartResource::collection($carts));
    }

    public function delete($id)
    {
        $cart = Cart::find($id);

        if ($cart) {
            $cart->delete();
            return $this->successResponse('delete cart success', $cart);
        } else {
            return $this->errorResponse('cart not found', null);
        }
    }
}
