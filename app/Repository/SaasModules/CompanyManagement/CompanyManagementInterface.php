<?php

namespace App\Repository\SaasModules\CompanyManagement;

use Illuminate\Http\Request;

interface CompanyManagementInterface {
    //discription: This method will be used to load all the companies with pagination
    public function index(Request $request);

    //discription: This method will be used to add new companies
    public function store(Request $request);

    //discription: This method will be used to update a company record
    //@param: id of the company which we want to update
    public function update($id, Request $request);

    //discription: This method will be used to delete the company
    //@param: id of the company which we want to delete
    public function destroy($id);

    //discription: This method will be used to show the company information from super admin (to populate edit form) 
    public function show($id);

    //discription: This method will be used to edit company from super admin side
    public function updateCompany($id, Request $request);

    //discription: This method will be used to show the company details to company admin
    public function showCompany();

}