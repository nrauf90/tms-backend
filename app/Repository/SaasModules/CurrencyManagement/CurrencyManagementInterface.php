<?php

namespace App\Repository\SaasModules\CurrencyManagement;

use Illuminate\Http\Request;

interface CurrencyManagementInterface {
    // discription: This method will be used to load all the currencies with pagination.
    public function index(Request $request);

    // discription: This method will be used to add new currency
    public function store(Request $request);

    //discription: This method will be used to edit the currency.
    // @param : id of the currency which we want to update
    public function update($id, Request $request);

    //discription: This method will be used to delete the currency.
    // @param: id of the currency which we want to delete
    public function destroy($id);

    //discription: This method will be used to fetch all the currencies without pagination usually used
    // to populate forms
    public function getAllCurrencies();
}