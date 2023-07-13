<?php

namespace App\Repository\OrderPayment;

use App\Models\Order;
use App\Models\OrderPayment;
use Illuminate\Support\Facades\Crypt;

class OrderPaymentRepository implements OrderPaymentRepositoryInterface
{
    public function create($request, $order)
    {
        $input_order_payment['order_id'] = $order->id;
        $input_order_payment['status'] = 'unpaid';
        $input_order_payment['payment_method'] = $request['payment_method'];
        $input_order_payment['payment_instruction_api'] = '';
        $input_order_payment['payment_body_detail'] = '';
        $input_order_payment['payment_header_detail'] = '';
        $input_order_payment['payment_type'] = $request['payment_type'];
        return OrderPayment::create($input_order_payment);
    }

    public function afterPayment($request)
    {
        $order = Order::where('transaction_code', $request['invoice_number'])->first();
        $order_payment = OrderPayment::where('order_id', $order->id)->first();
        $order_payment->paid_at = $request['paid_at'];
        $order_payment->payment_body_detail = Crypt::encryptString(\json_encode($request['body_detail']));
        $order_payment->payment_header_detail = Crypt::encryptString(\json_encode($request['header_detail']));
        $order_payment->status = 'paid';
        $order_payment->save();
    }
}
