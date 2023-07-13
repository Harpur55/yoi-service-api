<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreStoreTermAndConditionRequest extends FormRequest
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
            'store_id'                              => [''],
            'show_term_and_condition'               => ['required', 'numeric'],
            'term_description'                      => ['required'],
            'show_time_operational'                 => ['required', 'numeric'],
            'day_operation'                         => ['required'],
            'opening_time_operational'              => ['required'],
            'opening_time_operational_description'  => [''],
            'closing_time_operational'              => ['required'],
            'closing_time_operational_description'  => [''],
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
