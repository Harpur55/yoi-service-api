<?php

namespace App\Repository\Order;

use App\Http\Resources\BankAccountResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ProductDetailResource;
use App\Models\BankAccount;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Store;
use App\Models\StoreCourierShippingAddress;
use App\Repository\OrderDetail\OrderDetailRepositoryInterface;
use App\Repository\OrderPayment\OrderPaymentRepositoryInterface;
use App\Repository\OrderShipping\OrderShippingRepositoryInterface;
use App\Traits\ServiceResponseHandler;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class OrderRepository implements OrderRepositoryInterface
{
    use ServiceResponseHandler;

    protected 
        $orderDetailRepository,
        $orderPaymentRepository,
        $orderShippingRepository;

    public function __construct(
        OrderDetailRepositoryInterface $orderDetailRepository,
        OrderPaymentRepositoryInterface $orderPaymentRepository,
        OrderShippingRepositoryInterface $orderShippingRepository
    )
    {
        $this->orderDetailRepository = $orderDetailRepository;
        $this->orderPaymentRepository = $orderPaymentRepository;
        $this->orderShippingRepository = $orderShippingRepository;
    }

    public function getAll($request): object
    {
        $customer = Customer::where('user_id', Auth::id())->first();

        return $this->successResponse('Successfully fetch orders', OrderResource::collection(Order::where('customer_id', $customer->id)->where('is_cancelled', false)->orderBy('id', 'DESC')->get()));
    }

    public function getById($id): object
    {
        $order = Order::find($id);

        if (isset($order)) {
            return $this->successResponse('Successfully fetch order', new OrderResource($order));
        } else {
            return $this->errorResponse('Order not found', null);
        }
    }

    public function create($request): object
    {
        $validated_request = $request->validated();

        $product = Product::find($validated_request['product_id']);
        $customer = Customer::where('user_id', Auth::id())->first();

        $validated_request['seller_id'] = $product->store->seller->id;
        $validated_request['customer_id'] = $customer->id;
        $validated_request['transaction_code'] = 'TRCD-Y-' . $product->store->seller->id . $customer->id . Carbon::now()->getTimestampMs();
        $validated_request['receipt_number'] = "-";
        $order = Order::create($validated_request);

        $order_detail = $this->orderDetailRepository->create($validated_request, $order, $product);

        $order_payment = $this->orderPaymentRepository->create($validated_request, $order);
        
        $this->orderShippingRepository->create($validated_request, $order);

        $payment_response = \MakePaymentWithDOKU($validated_request, $order, $customer, $product);

        $order_payment->payment_instruction_api = $payment_response->how_to_pay_api;
        $order_payment->payment_created_at = Carbon::parse($payment_response->created_date);
        $order_payment->payment_expired_at = Carbon::parse($payment_response->expired_date);
        $order_payment->save();
        
        $output = (object) [
            'order'         => $order_detail,
            'payment'       => $order_payment,
            'instruction'   => $payment_response->instruction,
            'va_info'       => $payment_response->va_info,
            'expired_at'    => Carbon::parse($order_payment->payment_expired_at)->format('d F Y')
        ];

        return $this->successResponse('order payment init', $output);
    }

    public function calculate($request)
    {
        $customer = Customer::where('user_id', Auth::id())->first();
        
        $carts = Cart::with('detail')->where('customer_id', $customer->id)->whereIn('id', $request->cart_id)->get();
        $total = 0;
        $qty = 0;

        foreach ($carts as $cart) {
            $total += $cart->detail->amount * $cart->detail->price;
            $qty += 1;
        }

        $response = (object) [
            'total' => $total,
            'qty' => $qty
        ];

        return $this->successResponse('calculate success', $response);
    }

    public function init($request)
    {
        $customer = Customer::where('user_id', Auth::id())->first();
        $response = [];
        $store = [];

        $carts = Cart::with('detail', 'product.store')
                    ->where('customer_id', $customer->id)->whereIn('id', $request->cart_id)
                    ->get();

        foreach($carts as $cart) {
            if (!in_array($cart->product->store, $store)) {
                $store[] = $cart->product->store;
                
                $response[] = (object) [
                    'store' => $cart->product->store,
                    'cart'  => $cart
                ];
            } else {
                // $response[$cart->product->store] = (object) [
                //     'cart'  => $cart
                // ];
            }
        }

        return $this->successResponse('init success', $response);
    }

    public function directOrder($id)
    {
        $product = Product::find($id);
        $store_address = StoreCourierShippingAddress::where('store_id', $product->store->id)->where('is_active', true)->first();

        $customer = Customer::where('user_id', Auth::id())->first();
        $customer_address = CustomerAddress::where('is_active', true)->where('customer_id', $customer->id)->first();

        $response_cost = Http::post(env('COURIER_SERVICE_HOST') . '/cost', [
            'sub_origin' => $store_address->subdistrict_id,
            'sub_destination' => $customer_address->subdistrict_id,
            'weight'    => $product->detail->weight,
            "courier" => ["jne"]
        ]);

        $response = (object) [
            'product_detail'   => new ProductDetailResource($product->detail),
            'seller'           => $product->store->seller->user,
            'payment_method'   => BankAccountResource::collection(BankAccount::all()),
            'cost'             => \count($response_cost['data']) > 0 ? $response_cost['data'][0] : []
        ];

        return $this->successResponse('direct order success', $response);
    }

    public function callbackOrderPayment()
    {
        
    }

    public function fetchBySeller()
    {
        $seller = Seller::where('user_id', Auth::id())->first();

        return $this->successResponse('success fetch', OrderResource::collection(Order::where('seller_id', $seller->id)
        ->where('is_cancelled', false)
        ->where('is_approved_by_seller', false)
        ->where('is_waiting_shipping', false)
        ->whereHas('payment', function ($query) {
            $query->where('status', 'paid');
        })
        ->orderBy('id', 'DESC')->get()));
    }

    public function fetchBySellerForCancelled()
    {
        $seller = Seller::where('user_id', Auth::id())->first();

        return $this->successResponse('success fetch', OrderResource::collection(Order::where('seller_id', $seller->id)->where('is_cancelled', true)->orderBy('id', 'DESC')->get()));
    }

    public function cancelOrder($id)
    {
        $order = Order::find($id);
        $order->is_cancelled = true;
        $order->save();

        return $this->successResponse('success update', $order);
    }

    public function fetchBySellerForIsApproved()
    {
        $seller = Seller::where('user_id', Auth::id())->first();

        return $this->successResponse('success fetch', OrderResource::collection(Order::where('seller_id', $seller->id)->where('is_approved_by_seller', true)->where('is_waiting_shipping', false)->orderBy('id', 'DESC')->get()));
    }

    public function approveOrder($id)
    {
        $order = Order::find($id);
        $order->is_approved_by_seller = true;
        $order->save();

        return $this->successResponse('success update', $order);
    }

    public function fetchBySellerForIsWaitingShipping()
    {
        $seller = Seller::where('user_id', Auth::id())->first();

        return $this->successResponse('success fetch', OrderResource::collection(Order::where('seller_id', $seller->id)->where('is_waiting_shipping', true)->orderBy('id', 'DESC')->get()));
    }

    public function requestPickup($id)
    {
        $order = Order::find($id);
        $order->is_waiting_shipping = true;

        $seller = Seller::where('user_id', Auth::id())->first();
        $store = Store::where('seller_id', $seller->id)->first();

        $store_address = StoreCourierShippingAddress::where('store_id', $store->id)->where('is_active', true)->first();

        $customer = Customer::where('user_id', Auth::id())->first();
        $customer_address = CustomerAddress::where('is_active', true)->where('customer_id', $customer->id)->first();

        $url = env('COURIER_SERVICE_HOST') . '/city/branch';

        $params = [
            'id' => $customer_address->city_id
        ];

        $branch = Http::get($url, $params);

        $order_item = [];

        foreach ($order->detail as $detail) {
            $order_detail = OrderDetail::find($detail->id);

            $order_item[] = (object) [
                "code" => strval($detail->id),
                "name" => $order_detail->product->detail->name,
                "category"=> $order_detail->product->category->name,
                "weight" => $order_detail->product->detail->weight,
                "qty" => $order_detail->quantity,
                "desc" => "BARANG PECAH",
                "amount" => $order_detail->price
            ];
        }
        $response_pickup = Http::post(env('COURIER_SERVICE_HOST') . '/pickup', [
            "sub_origin"        => $store_address->subdistrict_id,
            "sub_destination"   => $customer_address->subdistrict_id,
            "courier"       => "jne",
            "pickup"        => (object) [
                "name"      => "YOI PICKUP",
                "date"      => Carbon::now()->format('Y-m-d'),
                "time"      => Carbon::now()->format('H:i:s'),
                "address1"  => $store_address->full_address,
                "address2"  => "",
                "address3"  => null,
                "district"  => $store_address->district_name,
                "city"      => $store_address->city_name,
                "vehicel"   => "MOTOR",
                "note"      => "",
                "longitude" =>  106.961527,
                "latitude"  => -6.355934,
                "contact" => (object) [
                    "name"  => $seller->user->name,
                    "email" => $seller->user->email,
                    "phone" => $seller->user->phone
                ],
                "notes" => null,
            ],
            "shipper"        => (object) [
                "name"      => $seller->profile->store_name,
                "address1"  => $store_address->full_address,
                "address2"  => "",
                "address3"  => "",
                "city" => $store_address->city_name,
                "zip" => "16968",
                "region" => $store_address->city_name,
                "country" => "INDONESIA",
                "note" => "",
                "longitude"=> 106.961382, 
                "latitude"=> -6.350573,
                "contact" => (object) [
                    "name"  => $seller->user->name,
                    "email" => $seller->user->email,
                    "phone" => $seller->user->phone
                ],
            ],
            "receiver"        => (object) [
                "name"      => $customer->user->name,
                "address1"  => $customer_address->full_address,
                "address2"  => "",
                "address3"  => "",
                "city" => $customer_address->city_name,
                "zip" => "16820",
                "region" => $customer_address->city_name,
                "country" => "INDONESIA",
                "note" => "",
                "longitude" => 106.990399,
                "latitude" => -6.420944,
                "contact" => (object) [
                    "name"  => $customer->user->name,
                    "email" => $customer->user->email,
                    "phone" => $customer->user->phone
                ],
            ],
            "item" => $order_item,
            "lat" => null,
            "lon" => null,
            "jne" => (object) [
                "cust_id" => "80802900", // FIXED VALUE
                "order_id" => strval($order->id), // BASED ON OUR SYSTEM
                "service_code" => $order->shipping->service_code, // FROM COST
                "insurance_flag" => "0", // OPTIONAL, JUST FILL WITH THIS VALUE
                "branch" => $branch['data'], // FROM JNE BRANCH TABLE IN OUR DATABASE
                "special_ins" => "TEST", // OPTIONAL, JUST FILL WITH THIS VALUE
                "merchant_id" => "SLRCD", // PREFIX FROM OUR SYSTEM
                "type" => "PICKUP", // PICKUP TYPE
                "cod_flag" => "NO", // OPTIONAL
                "cod_amount" => "30000", // OPTIONAL
                "note" => "Barang Pecah" // FROM SELLER
            ]
        ]);

        $order->receipt_number = $response_pickup['data'][0]['data']['detail'][0]['cnote_no'];
        $order->merchant_id = $response_pickup['data'][0]['merchant_id'];
        $order->save();

        return $this->successResponse('success update', $response_pickup);
    }
}
