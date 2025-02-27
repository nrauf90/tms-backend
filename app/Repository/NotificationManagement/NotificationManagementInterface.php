<?php

namespace App\Repository\NotificationManagement;

use Illuminate\Http\Request;

interface NotificationManagementInterface {

//discription: This method will get all the notifications aganist the logged in user
    public function index(Request $request);

//discription : This method will get all the unread notifications of a specific user
// @param id in this function will be logged in user's id
    public function getUnreadNotifications();

//discription: This method will be used to store new notifications.
    public function store(Request $request);

//discription: This method will show a specific notification to a user
    public function show($id);

// discription: This method will change the status of all notifications i.e. (Unread to Read)
// @param id in this function will be the user id aganist which all the notifications in table will be marked read to unread & vice versa.
    public function readAllNotifications($id);

//discription: This method will change the status of a specific notification i.e. (Unread to Read)
// @param id in this function will be the id of the specific notification
    public function readNotification($id);

//discription: This method will be used to delete the notification
// @param id in this function is the id of the notification
    public function destroy($id);
}