<?php

namespace App\Http\Controllers;

use App\Models\CallbackCourier;
use Illuminate\Http\Request;

class CallbackCourierController extends Controller
{
    public function callback(Request $request)
    {
        $request = [
            'hitted' => 'true'
        ];

        CallbackCourier::create($request);   
    }
}
