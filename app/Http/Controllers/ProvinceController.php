<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Http;

class ProvinceController extends Controller
{
    public function index()
    {
        try {
            $url = env('COURIER_SERVICE_HOST') . '/province';

            $response_province = Http::withHeaders([
                'Authorization' => env('COURIER_SERVICE_API_KEY')
            ])->get($url);

            return $this->successResponse('fetch province success', $response_province['data']);
        } catch (Exception $e) {
            return $this->serverErrorResponse($e->getMessage());
        }
    }
}
