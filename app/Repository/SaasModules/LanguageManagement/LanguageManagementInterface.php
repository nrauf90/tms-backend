<?php

namespace App\Repository\SaasModules\LanguageManagement;

use Illuminate\Http\Request;

interface LanguageManagementInterface {
    
    //discription: This method will be used to load all the languages
    public function index(Request $request);

    //discription: This method will be used to add new language
    public function store(Request $request);

    //discription: This method will be used to update the language
    //@param: id of the language which we want to update
    public function update($id, Request $request);

    //discription: This method will be used to delete the language 
    public function destroy($id);
}