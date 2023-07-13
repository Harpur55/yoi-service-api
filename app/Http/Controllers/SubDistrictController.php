<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Http;

class SubDistrictController extends Controller
{
    public function index()
    {
        try {
            $district_id = request()->get('district_id');

            if ($district_id) {
                $url = env('COURIER_SERVICE_HOST') . '/district/sub';

                $params = [
                    'district_id' => $district_id
                ];

                $response_province = Http::withHeaders([
                    'api_key' => env('COURIER_SERVICE_API_KEY')
                ])->get($url, $params);
    
                return $this->successResponse('fetch sub district success', $response_province['data']);
            } else {
                return $this->errorResponse('district id cannot be emtpy', null);
            }
        } catch (Exception $e) {
            return $this->serverErrorResponse($e->getMessage());
        }
    }
}
