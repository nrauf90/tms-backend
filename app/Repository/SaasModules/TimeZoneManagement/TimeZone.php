<?php

namespace App\Repository\SaasModules\TimeZoneManagement;

use Illuminate\Http\Request;
use App\Models\Timezone as TimeZones;

class TimeZone implements TimeZoneManagementInterface {

    public function index(Request $request) {
        if($request->timeZone == null){
            $timeZones = TimeZones::get();
    
            if($timeZones->isEmpty()){
                $response["success"] = false;
                $response["message"] = "No results found";
            }
            else {
                $response["success"] = true;
                $response["message"] = "Results found successfully";
                $response["data"] = $timeZones;
            }
        } else {
            $timeZone = $request->timeZone;
            $timeZones = TimeZones::where(function ($query) use ($timeZone){
                if($timeZone != null){
                    return $query->where('time_zone', 'LIKE', '%'.$timeZone.'%');
                }
            })->get();
            if(empty($timeZones)){
                $response["success"] = false;
                $response["message"] = "No results found";
            } else {
                $response["success"] = true;
                $response["message"] = "Results found successfully";
                $response["data"] = $timeZones;
            }
        }
        return $response;
    }

    public function store(Request $request){
        $check = TimeZones::where('time_zone', $request->time_zone)
        ->where('GMT', $request->GMT)
        ->get();

        if($check->isEmpty()){
            $newTimeZone = new TimeZones;
            $newTimeZone->time_zone = $request->time_zone;
            $newTimeZone->GMT = $request->GMT;
            $newTimeZone->save();

            if(!$newTimeZone){
                $response["success"] = false;
                $response["message"] = "Data Not Saved";
            }
            else {
                $response["success"] = true;
                $response["message"] = "Data has been saved successfully";
                $response["data"] = $newTimeZone;
            }
        } else {
            $response["success"] = false;
            $response["message"] = "This time zone already exists";
        }

        return $response;
    }

    public function update($id, Request $request){
        $timezone = TimeZones::find($id);

        if(empty($timezone)){
            $response["success"] = false;
            $response["message"] = "No results found";
        } else {
            $timezone->time_zone = $request->time_zone;
            $timezone->GMT = $request->GMT;
            $timezone->save();

            if(!$timezone){
                $response["success"] = false;
                $response["message"] = "Unable to update time zone";
            } else {
                $response["success"] = true;
                $response["message"] = "Time zone updated successfully";
                $response["data"] = $timezone;
            }
        }

        return $response;
    }

    public function destroy($id){
        $timezone = TimeZones::find($id);

        if(empty($timezone)){
            $response["success"] = false;
            $response["message"] = "No results found";
        } else {
            $timezone->delete();
            $response["success"] = true;
            $response["message"] = "Time zone deleted successfully";
            $response["data"] = $timezone;
        }

        return $response;
    }
}