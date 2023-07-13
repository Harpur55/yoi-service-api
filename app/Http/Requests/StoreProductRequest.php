<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreProductRequest extends FormRequest
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
     * @param StoreProductDetailRequest $storeProductDetailRequest
     * @return array
     */
    public function rules(StoreProductDetailRequest $storeProductDetailRequest): array
    {
        $storeProductDetailRules = $storeProductDetailRequest->rules();
        $storeProductRules = [
            'product_category_id'   => ['required', 'numeric'],
        ];

        return array_merge($storeProductRules, $storeProductDetailRules);
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
