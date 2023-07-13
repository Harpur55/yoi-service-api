<?php

use App\Enums\DOKUO2OEnum;
use App\Enums\DOKUPaymentTypeEnum;
use App\Enums\DOKUVirtualAccountEnum;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Jawak\DokuLaravel\Facades\Doku;

function ImageUploader($image, $category): string
{
    $locator_category = $category . '_image_location';
    $location = config('assets.' . $locator_category);

    $image_path = $image->store($location);
    $image_name = str_replace($location , "", $image_path);
    $image->move(public_path($location), $image_name);

    return $image_name;
}

function RemoveImage($image_name, $category)
{
    $locator_category = $category . '_image_location';
    $location = config('assets.' . $locator_category);

    return unlink(public_path($location . $image_name));
}

function IconUploader($icon, $type): string
{
    $locator_type = $type . '_icon_location';
    $location = config('assets.' . $locator_type);

    $icon_path = $icon->store($location);
    $icon_name = str_replace($location , "", $icon_path);
    $icon->move(public_path($location), $icon_name);

    return $icon_name;
}

function RemoveIcon($icon_name, $type)
{
    $locator_type = $type . '_icon_location';
    $location = config('assets.' . $locator_type);

    return unlink(public_path($location . $icon_name));
}

function MakePaymentWithDOKU($request, $order, $customer, $product)
{   
    switch($request['payment_type']) {
        case DOKUPaymentTypeEnum::VA->value:
            $response = DOKUVirtualAccountPayment($request, $order, $customer, $product);

            $response_instructions = Http::withHeaders([
                ])->get(\json_decode($response)->virtual_account_info->how_to_pay_api);
            
            return (object) [
                'how_to_pay_api'    => json_decode($response)->virtual_account_info->how_to_pay_api,
                'created_date'      => isset(json_decode($response)->virtual_account_info->created_date) ? json_decode($response)->virtual_account_info->created_date : json_decode($response)->virtual_account_info->created_date_utc,
                'expired_date'      => isset(json_decode($response)->virtual_account_info->expired_date) ? json_decode($response)->virtual_account_info->expired_date : json_decode($response)->virtual_account_info->expired_date_utc,
                'instruction'       => $response_instructions['payment_instruction'],
                'va_info'           => $response['virtual_account_info']
            ];
        case DOKUPaymentTypeEnum::CC->value:
            // $response = DOKUCreditCardPayment($request, $order, $customer, $product);
            
            return null;
        case DOKUPaymentTypeEnum::O2O->value:
            $response = DOKUO2OPayment($request, $order, $customer, $product);
            
            Log::debug($response);

            // $response_instructions = Http::withHeaders([
            //     ])->get(\json_decode($response)->virtual_account_info->how_to_pay_api);
            
            // return (object) [
            //     'how_to_pay_api'    => json_decode($response)->virtual_account_info->how_to_pay_api,
            //     'created_date'      => isset(json_decode($response)->virtual_account_info->created_date) ? json_decode($response)->virtual_account_info->created_date : json_decode($response)->virtual_account_info->created_date_utc,
            //     'expired_date'      => isset(json_decode($response)->virtual_account_info->expired_date) ? json_decode($response)->virtual_account_info->expired_date : json_decode($response)->virtual_account_info->expired_date_utc,
            //     'instruction'       => $response_instructions['payment_instruction']
            // ];
            return null;
        case DOKUPaymentTypeEnum::EMONEY->value:
            // $response = DOKUEMoneyPayment($request, $order, $customer, $product);
            
            // $response_instructions = Http::withHeaders([
            //     ])->get(\json_decode($response)->virtual_account_info->how_to_pay_api);
            
            // return (object) [
            //     'how_to_pay_api'    => json_decode($response)->virtual_account_info->how_to_pay_api,
            //     'created_date'      => isset(json_decode($response)->virtual_account_info->created_date) ? json_decode($response)->virtual_account_info->created_date : json_decode($response)->virtual_account_info->created_date_utc,
            //     'expired_date'      => isset(json_decode($response)->virtual_account_info->expired_date) ? json_decode($response)->virtual_account_info->expired_date : json_decode($response)->virtual_account_info->expired_date_utc,
            //     'instruction'       => $response_instructions['payment_instruction']
            // ];
            return null;
        default:
            return null;
    }
}

function DOKUVirtualAccountPayment($request, $order, $customer, $product)
{
    $other_va_info = [
        "expired_time" => 1440,
        "reusable_status" => false
    ];

    $bni_va_info = [
        "expired_time" => 1440,
        "reusable_status" => false,
        "merchant_unique_reference" => 'REF-ORD-' . rand(1, 9999)
    ];

    $params = [
        "order" => [
            "invoice_number" => $order->transaction_code,
            'amount' => $request['total_price']
        ],
        "virtual_account_info" => $request['payment_method'] == DOKUVirtualAccountEnum::BNI->value ? $bni_va_info : $other_va_info,
        "customer" => [
            "name" => $customer->user->name,
            "email" => $customer->user->email
        ]
    ];

    /**
     * BCA
     */
    switch($request['payment_method']) {
        case DOKUVirtualAccountEnum::BCA->value:
            return Doku::VA()->bca($params);
        case DOKUVirtualAccountEnum::BNI->value:
            return Doku::VA()->bni($params);
        case DOKUVirtualAccountEnum::BRI->value:
            return Doku::VA()->bri($params);
        case DOKUVirtualAccountEnum::BSI->value:
            return Doku::VA()->bsi($params);
        case DOKUVirtualAccountEnum::CIMB->value:
            return Doku::VA()->cimb($params);
        case DOKUVirtualAccountEnum::DANAMAON->value:
            return Doku::VA()->danamon($params);
        case DOKUVirtualAccountEnum::DOKU->value:
            return Doku::VA()->doku($params);
        case DOKUVirtualAccountEnum::MANDIRI->value:
            return Doku::VA()->mandiri($params);
        case DOKUVirtualAccountEnum::PERMATA->value:
            return Doku::VA()->permata($params);
        default:
            return null;
    }
}

function DOKUCreditCardPayment($request, $order, $customer, $product)
{

}

function DOKUO2OPayment($request, $order, $customer, $product)
{
    $params = [
        "order" => [
            "invoice_number" => $order->transaction_code,
            'amount' => $request['quantity'] * $product->detail->price
        ],
        "online_to_offline_info" => [
            "expired_time" => 60,
            "reusable_status" => true,
            "info" => "Thanks for shopping in Yeekhan Original Indonesia"
        ],
        "customer" => [
            "name" => $customer->user->name,
            "email" => $customer->user->email
        ],
        "indomaret_info" => [
            "receipt" => [
                "description" => "ORDER 001",
                "footer_message" => "Yeekhan Original Indonesia"
            ]
        ]
    ];

    switch($request['payment_method']) {
        case DOKUO2OEnum::INDOMARET->value:
            return Doku::Gerai()->indomaret($params);
        case DOKUO2OEnum::ALFA->value:
            return Doku::Gerai()->alfa($params);
        default:
            return null;
    }
}

function DOKUEMoneyPayment($request, $order, $customer, $product)
{

}

function RandNumberGenerator($length) {
    $number = '0123456789';
    $num_length = strlen($number);
    $result = '';

    for ($i = 0; $i < $length; $i++) {
        $result .= $number[random_int(0, $num_length - 1)];
    }

    return $result;
}
