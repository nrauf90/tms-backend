<?php

namespace App\Repository\NotificationManagement;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Events\Notify;

class Notifications implements NotificationManagementInterface {

    public function index(Request $request){
        $notifications = Notification::where('receiver_id', auth('sanctum')->user()->id)->OrderBy('created_at', 'desc')->get();
        if($notifications->isEmpty()){
            $response["success"] = false;
            $response["message"] = "No Results Found !";
        }
        else {
            $response["success"] = true;
            $response["message"] = "Results Found Successfully";
            $response["data"] = $notifications;
        }
        return $response;
    }

    public function getUnreadNotifications(){
        //dd(auth('sanctum')->user()->id);
        $notifications = Notification::where('receiver_id', auth('sanctum')->user()->id)->where('status', 0)->get();

        if($notifications->isEmpty()){
            $response["success"] = true;
            $response["message"] = "No New Notifications";
            $response["data"] = "No Unread Notifications";
        } else {
            $response["success"] = true;
            $response["message"] = "Data Found Successfully";
            $response["data"] = $notifications;
        }

        return $response;
    }

    public function store(Request $request){
        $notification = new Notification ();
        $notification->type = $request->type;
        $notification->description = $request->description;
        $notification->receiver_id = $request->receiver_id;
        $notification->sender_id = $request->sender_id;
        $notification->status = 0;
        $notification->save();

        if(!$notification) {
            $response["success"] = false;
            $response["message"] = "Data Not Saved";
        }
        else {
            event(new Notify($request->receiver_id));
            $response["success"] = true;
            $response["message"] = "Data has been saved successfully & event is dispatched";
            $response["data"] = $notification;
        }
        return $response;

    }

    public function show($id){

        $notification = Notification::where('id', $id)->get();
        if($notification->isEmpty()){
            $response["success"] = false;
            $response["message"] = "Record not found !";
        } else {
            $response["success"] = true;
            $response["message"] = "Record Found";
            $response["data"] = $notification;
        }
        return $response;
    }

    public function readAllNotifications($id){
        $notifications = Notification::where('receiver_id', $id)->get();

        if($notification->isEmpty()){
            $response["success"] = false;
            $response["message"] = "No new notifications to read !";
        } else {
            foreach($notifications as $notification) {
                if($notification->status == 0){
                    $currentNotification = Notification::find($notification->id);
                    $currentNotification->status = 1;
                    $currentNotification->save();
                }
            }
            $response["success"] = true;
            $response["message"] = "All notifications are marked as read";
            $response["data"] = Notification::where('receiver_id', $id)->get();
        }
        return $response; 
    }

    public function readNotification($id){
        $notification = Notification::find($id);

        if(empty($notification)){
            $response["success"] = false;
            $response["message"] = "Data not found";
        } else {
            $notification->status == 1;
            $notification->save();
            $response["success"] = true;
            $response["message"] = "Status changed successfully";
            $response["data"] = $notification;
        }
        return $response;
    }

    public function destroy($id){
        $notification = Notification::find($id);

        if(empty($notification)){
            $response["success"] = false;
            $response["message"] = "Data not found";
        } else {
            $notification->delete();
            $response["success"] = true;
            $response["message"] = "Record deleted successfully";
            $response["data"] = $notification;
        }
        return $response;
    }
}