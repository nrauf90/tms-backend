<?php

namespace App\Repository\SaasModules\WeekManagement;

use Illuminate\Http\Request;

interface WeekManagementInterface{
    //discription: This method will be used to get all the weekdays.
    public function index(Request $request);
}