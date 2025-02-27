<?php

namespace App\Repository\SaasModules\LanguageManagement;

use Illuminate\Http\Request;
use App\Models\Language;

class Languages implements LanguageManagementInterface{

    public function index(Request $request){
        if($request->languageName == null){
            $languages = Language::get();

            if($languages->isEmpty()){
                $response["success"] = false;
                $response["message"] = "No results found";
            } else {
                $response["success"] = true;
                $response["message"] = "Results found successfully";
                $response["data"] = $languages;
            }
        } else {
            $languageName = $request->languageName;
            $languages = Language::where(function ($query) use ($languageName){
                if($languageName != null){
                    return $query->where('language_name', 'LIKE', '%'.$languageName.'%');
                }
            })->get();

            if(empty($languages)){
                $response["success"] = false;
                $response["message"] = "No results found";
            } else {
                $response["success"] = true;
                $response["message"] = "Results found successfully";
                $response["data"] = $languages;
            }
        }

        return $response;
    }

    public function store(Request $request){
        $check = Language::where('language_name', $request->data['languageName'])
        ->where('language_code', $request->data['languageCode'])
        ->get();

        if($check->isEmpty()){
            $language = new Language;
            $language->language_name = $request->data['languageName'];
            $language->language_code = $request->data['languageCode'];
            $language->save();

            if(! $language){
                $response["success"] = false;
                $response["message"] = "Data not saved";
            } else {
                $response["success"] = true;
                $response["message"] = "Data has been saved successfully";
                $response["data"] = $language;
            }
        } else {
            $response["success"] = false;
            $response["message"] = "This language already exists";
        }

        return $response;
    }

    public function update($id, Request $request){
        $language = Language::find($id);

        if(empty($language)){
            $response["success"] = false;
            $response["message"] = "No results found";
        } else {
            $language->language_name = $request->data['languageName'];
            $language->language_code = $request->data['languageCode'];
            $language->save();

            if(! $language){
                $response["success"] = false;
                $response["message"] = "Unable to update language";
            } else {
                $response["success"] = true;
                $response["message"] = "Language updated successfully";
                $response["data"] = $language;
            }
        }

        return $response;
    }

    public function destroy($id){
        $language = Language::find($id);

        if(empty($language)){
            $response["success"] = false;
            $response["message"] = "No results found";
        } else {
            $language->delete();
            $response["success"] = true;
            $response["message"] = "Language Deleted Successfully";
            $response["data"] = $language;
        }

        return $response;
    }
}