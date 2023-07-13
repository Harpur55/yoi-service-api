<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Http;

class DistrictController extends Controller
{
    public function index()
    {
        try {
            $city_id = request()->get('city_id');

            if ($city_id) {
                $url = env('COURIER_SERVICE_HOST') . '/district';

                $params = [
                    'city_id' => $city_id
                ];

                $response_province = Http::withHeaders([
                    'api_key' => env('COURIER_SERVICE_API_KEY')
                ])->get($url, $params);
    
                return $this->successResponse('fetch district success', $response_province['data']);
            } else {
                return $this->errorResponse('city id cannot be emtpy', null);
            }
        } catch (Exception $e) {
            return $this->serverErrorResponse($e->getMessage());
        }
    }
}
