<?php

namespace App\Http\Controllers;

use App\Repository\OrderPayment\OrderPaymentRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class OrderPaymentController extends Controller
{
    protected $orderPaymentRepository;

    public function __construct(OrderPaymentRepositoryInterface $orderPaymentRepository)
    {
        $this->orderPaymentRepository = $orderPaymentRepository;
    }

    public function callback(Request $request)
    {
        $clientID = $request->header('client-id') != "" ? $request->header('client-id') : null;
        $requestID = $request->header('request-id') != "" ? $request->header('request-id') : null;
        $requestTimestamp = $request->header('request-timestamp') != "" ? $request->header('request-timestamp') : null;
        $signature = $request->header('signature') != "" ? $request->header('signature') : null;

        if (
            $clientID &&
            $requestID &&
            $requestTimestamp &&
            $signature
        ) {
            $digest = base64_encode(hash('sha256', file_get_contents('php://input'), true));
            $rawSignature = "Client-Id:" . $clientID . "\n"
            . "Request-Id:" . $requestID . "\n"
            . "Request-Timestamp:" . $requestTimestamp . "\n"
            . "Request-Target:" . '/api/v1/payment/callback' . "\n"
            . "Digest:" . $digest;

            $_signature = base64_encode(hash_hmac('sha256', $rawSignature, env('DOKU_SECRET_KEY'), true));
            $finalSignature = 'HMACSHA256=' . $_signature;
    
            if ($finalSignature == $signature) {
                $request_after_payment = [
                    "invoice_number" => $request->order['invoice_number'],
                    "paid_at"        => Carbon::now(),
                    "body_detail"    => $request->all(),
                    "header_detail"  => $request->header()
                ];

                $this->orderPaymentRepository->afterPayment($request_after_payment);

                return $this->successResponse('callback success', null);
            } else {
                return $this->errorResponse('invalid signature', null);
            }
        } else {
            return $this->errorResponse('invalid callback', null);
        }
    }
}
