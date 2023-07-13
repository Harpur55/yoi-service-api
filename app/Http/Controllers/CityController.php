<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Http;

class CityController extends Controller
{
    public function index()
    {
        try {
            $province_id = request()->get('province_id');

            if ($province_id) {
                $url = env('COURIER_SERVICE_HOST') . '/city';

                $params = [
                    'province_id' => $province_id
                ];

                $response_province = Http::withHeaders([
                    'api_key' => env('COURIER_SERVICE_API_KEY')
                ])->get($url, $params);
    
                return $this->successResponse('fetch city success', $response_province['data']);
            } else {
                return $this->errorResponse('province id cannot be emtpy', null);
            }
        } catch (Exception $e) {
            return $this->serverErrorResponse($e->getMessage());
        }
    }
}
