<?php

namespace App\Repository\SaasModules\CountryManagement;

use Illuminate\Http\Request;

interface CountryManagementInterface {

    //discription: This method will be used to fetch all the countries
    public function index(Request $request);
}