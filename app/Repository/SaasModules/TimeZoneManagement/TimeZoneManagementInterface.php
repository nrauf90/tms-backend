<?php

namespace App\Repository\SaasModules\TimeZoneManagement;

use Illuminate\Http\Request;

interface TimeZoneManagementInterface{

    //discription : This method will be used to load all timezones
    public function index(Request $request);

    //discription: This method will be used to add new timezone
    public function store(Request $request);

    //discription: This method will be used to update timezone
    //@param: id of the timezone which we want to update
    public function update($id, Request $request);

    //discription: This method will be used to delete the timezone
    //@param: id of the timezone which we want to delete.
    public function destroy($id);
}