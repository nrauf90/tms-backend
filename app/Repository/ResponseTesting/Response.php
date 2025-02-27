<?php

namespace App\Repository\ResponseTesting;

use Illuminate\Http\Request;
use App\Models\Currency;
use App\Traits\ApiResponse;

class Response implements ResponseTestingInterface{

    use ApiResponse;

    public function index(Request $request){
        $currencies = Currency::paginate(10);

        if($currencies->isEmpty()){
            return $this->errorResponse(getStatusCode('BASIC', 'NOT_FOUND'), getStatusCode('HTTP', 'SUCCESS'), 'NO RECORD FOUND');
        } else {
            return $this->successResponse($currencies, getStatusCode('BASIC', 'SUCCESS'), getStatusCode('HTTP', 'SUCCESS'), 'Currencies List', Currency::count());
        }
    }
}