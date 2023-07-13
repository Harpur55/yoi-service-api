<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreStoreCourierShippingAddressRequest extends FormRequest
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
            'store_id'          => [''],
            'receiver_name'     => ['required'],
            'receiver_phone'    => ['required', 'numeric'],
            'city'              => ['required'],
            'district'          => ['required'],
            'full_address'      => ['required'],
            'latitude'          => [''],
            'longitude'         => [''],
            'prov_id'           => [''],
            'prov_name'         => [''],
            'city_id'           => [''],
            'city_name'         => [''],
            'district_id'        => [''],
            'district_name'         => [''],
            'subdistrict_id'           => [''],
            'subdistrict_name'         => ['']
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
