<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repository\ResponseTesting\ResponseTestingInterface;
use OpenApi\Annotations as OA;
use App\Http\Controllers\API\BaseController as BaseController;

class ResponseTestingController extends BaseController
{
    public $repositoryObj;

    public function __construct(ResponseTestingInterface $responseObj){
        $this->repositoryObj = $responseObj;
    }

    public function index(Request $request){
        return $this->repositoryObj->index($request);
    }
}
