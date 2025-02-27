<?php

namespace App\Repository\SaasModules\ModuleManagement;

use Illuminate\Http\Request;

interface ModuleManagementInterface{
    //discription: This method will be used to get all the modules data.
    public function index(Request $request);
}