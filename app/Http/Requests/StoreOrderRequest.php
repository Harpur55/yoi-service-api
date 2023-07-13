<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'customer_id'       => [],
            'seller_id'         => [],
            'product_id'         => [],
            'quantity'           => [],
            'price'              => [],
            'discount'           => [],
            'payment_method'     => [],
            'etd'   => [],
            'service_code' => [],
            'service_name' => [],
            'courier_code' => [],
            'courier_name' => [],
            'courier_price' => [],
            'origin_code' => [],
            'destination_code' => [],
            'payment_type'  => [],
            'receipt_number' => [],
            'total_price'=> []
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @param Validator $validator
     * @return array
     */
    protected function failedValidation(Validator $validator): array
    {
        throw new HttpResponseException(response()->json([
            'status'    => false,
            'message'   => $validator->errors(),
            'data'      => null
        ], 422));
    }
}
