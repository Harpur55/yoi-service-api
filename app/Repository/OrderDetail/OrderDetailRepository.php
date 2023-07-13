<?php

namespace App\Repository\OrderDetail;

use App\Models\OrderDetail;
use App\Traits\ServiceResponseHandler;

class OrderDetailRepository implements OrderDetailRepositoryInterface
{
    use ServiceResponseHandler;

    public function getAll($request): object
    {
        return $this->successResponse('Successfully fetch order details', OrderDetail::all());
    }

    public function getById($id): object
    {
        $order_detail = OrderDetail::find($id);

        if (isset($order_detail)) {
            return $this->successResponse('Successfully fetch order detail', $order_detail);
        } else {
            return $this->errorResponse('Order detail not found', null);
        }
    }

    public function create($request, $order, $product): object
    {
        $input_order_detail['order_id'] = $order->id;
        $input_order_detail['product_id'] = $request['product_id'];
        $input_order_detail['quantity'] = $request['quantity'];
        $input_order_detail['price'] = $product->detail->price;
        $input_order_detail['discount'] = 0;
        $input_order_detail['total_price'] = $request['total_price'];
        return OrderDetail::create($input_order_detail);
    }
}
