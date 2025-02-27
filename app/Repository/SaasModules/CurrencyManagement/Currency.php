<?php

namespace App\Repository\SaasModules\CurrencyManagement;

use Illuminate\Http\Request;
use App\Models\Currency as Currencies;

class Currency implements CurrencyManagementInterface {

    public function index(Request $request){
        if($request->currencyName == null){
        $currencies = Currencies::paginate($request->length);

        if($currencies->isEmpty()){
            $response["success"] = false;
            $response["message"] = "No results found";
        }
        else {
            $response["success"] = true;
            $response["message"] = "Results found successfully";
            $response["data"] = $currencies;
        }
    } else {
        $currency = $request->currencyName;
        $currencies = Currencies::where(function ($query) use ($currency){
            if($currency != null){
                return $query->where('currency', 'LIKE', '%'.$currency.'%');
            }
        })->paginate($request->length);
        if(empty($currencies)){
            $response["success"] = false;
            $response["message"] = "No results found";
        } else {
            $response["success"] = true;
            $response["message"] = "Results found successfully";
            $response["data"] = $currencies;
        }
    }

        return $response;
    }

    public function store(Request $request){
        $check = Currencies::where('currency', $request->data['currencyName'])
        ->where('code', $request->data['currencyCode'])
        ->where('symbol', $request->data['currencySymbol'])
        ->get();

        if($check->isEmpty()){
            $newCurrency = new Currencies;
            $newCurrency->currency = $request->data['currencyName'];
            $newCurrency->code = $request->data['currencyCode'];
            $newCurrency->symbol = $request->data['currencySymbol'];
            $newCurrency->is_cryptocurrency = $request->data['cryptoCurrency'];
            $newCurrency->save();

            if(!$newCurrency){
                $response["success"] = false;
                $response["message"] = "Data Not Saved";
            }
            else {
                $response["success"] = true;
                $response["message"] = "Data has been saved successfully";
                $response["data"] = $newCurrency;
            }
        } else {
            $response["success"] = false;
            $response["message"] = "This currency already exists";
        }

        return $response;
    }

    public function update($id, Request $request){
        $currency = Currencies::find($id);

        if(empty($currency)){
            $response["success"] = false;
            $response["message"] = "No results found";
        } else {
            $currency->currency = $request->data['currencyName'];
            $currency->code = $request->data['currencyCode'];
            $currency->symbol = $request->data['currencySymbol'];
            $currency->is_cryptocurrency = $request->data['cryptoCurrency'];
            $currency->save();

            if(!$currency){
                $response["success"] = false;
                $response["message"] = "Unable to update currency";
            } else {
                $response["success"] = true;
                $response["message"] = "Currency updated successfully";
                $response["data"] = $currency;
            }
        }

        return $response;
    }

    public function destroy($id){
        $currency = Currencies::find($id);

        if(empty($currency)){
            $response["success"] = false;
            $response["message"] = "No results found";
        } else {
            $currency->delete();
            $response["success"] = true;
            $response["message"] = "Currency deleted successfully";
            $response["data"] = $currency;
        }

        return $response;
    }

    public function getAllCurrencies(){
        $currencies = Currencies::get();

        if($currencies->isEmpty()){
            $response["success"] = false;
            $response["message"] = "No results found";
        } else {
            $response["success"] = true;
            $response["message"] = "Results found successfully";
            $response["data"] = $currencies;
        }

        return $response;
    }
}