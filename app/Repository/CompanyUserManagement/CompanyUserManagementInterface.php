<?php

namespace App\Repository\CompanyUserManagement;

use Illuminate\Http\Request;

interface CompanyUserManagementInterface{

    //discription: This method will be used to fetch the company users with pagination
    public function index(Request $request);

    //discription: This method will be used to store & register company user with its relevant roles
    public function store(Request $request);

    //discription: This method will be used to update the company user detail and its role
    //@param: id of the user which we want to update
    public function update($id, Request $request);

    //discription: This method will be used to delete the company user
    //@param: id of the user which we want to delete
    public function destroy($id);
    
}