<?php

namespace App\Repository\OrderShipping;

use App\Models\OrderShipping;

class OrderShippingRepository implements OrderShippingRepositoryInterface {
    
    public function create($request, $order)
    {
        $input_order_shipping['order_id'] = $order->id;
        $input_order_shipping['etd'] = $request['etd'];
        $input_order_shipping['service_code'] = $request['service_code'];
        $input_order_shipping['service_name'] = $request['service_name'];
        $input_order_shipping['courier_code'] = $request['courier_code'];
        $input_order_shipping['courier_name'] = $request['courier_name'];
        $input_order_shipping['price'] = $request['courier_price'];
        $input_order_shipping['order_shipping_code'] = 'ORD-Y-' . rand(1, 99999) . rand(1, 99999);
        $input_order_shipping['origin_code'] = rand(1, 99999);
        $input_order_shipping['destination_code'] = rand(1, 99999);
        return OrderShipping::create($input_order_shipping);
    }
}