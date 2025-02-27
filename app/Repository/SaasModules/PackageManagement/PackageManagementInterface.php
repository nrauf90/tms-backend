<?php

namespace App\Repository\SaasModules\PackageManagement;

use Illuminate\Http\Request;

interface PackageManagementInterface{
    //discription: This method will be used to load all the packages for a particular user.
    public function index(Request $request);

    //discription: This method will be used to create & store new packages
    public function store(Request $request);

    //discription: This method will be used to edit & update the package
    //@param: id of the package which we want to update.
    public function update($id, Request $request);

    //discription: This method will be used to delete the package and its relevant information.
    public function destroy($id);

    //discription: This method will be used to get information package information.
    public function details($id);

    //discription: This method will be used to get all the packages without pagination
    //which can be used to populate drop-downs in form
    public function allPackages();
}