<?php

namespace App\Repository\ResponseTesting;

use Illuminate\Http\Request;

interface ResponseTestingInterface {

    public function index(Request $request);
}