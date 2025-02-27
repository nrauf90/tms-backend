<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repository\NotificationManagement\NotificationManagementInterface;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Notifications",
 *     description="API Endpoints for Notification Management"
 * )
 */
class NotificationController extends BaseController
{
    public $repositoryObj;

    public function __construct(NotificationManagementInterface $notificationObj){

        $this->repositoryObj = $notificationObj;
    }

    /**
     * @OA\Get(
     *     path="/api/notifications",
     *     tags={"Notifications"},
     *     summary="Get all notifications",
     *     description="Returns list of all notifications for the authenticated user",
     *     security={{"apiAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="type", type="string"),
     *                 @OA\Property(property="message", type="string"),
     *                 @OA\Property(property="read_at", type="string", format="date-time", nullable=true),
     *                 @OA\Property(property="created_at", type="string", format="date-time")
     *             )),
     *             @OA\Property(property="message", type="string", example="Notifications retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No notifications found"
     *     )
     * )
     */
    public function index(Request $request){
        $response = $this->repositoryObj->index($request);

        if(! $response["success"]){
            return $this->sendError('Record Not Found.' , ['error' => $response["message"]]);
        }

        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Get(
     *     path="/api/unreadNotifications",
     *     tags={"Notifications"},
     *     summary="Get unread notifications",
     *     description="Returns list of unread notifications for the authenticated user",
     *     security={{"apiAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="type", type="string"),
     *                 @OA\Property(property="message", type="string"),
     *                 @OA\Property(property="created_at", type="string", format="date-time")
     *             )),
     *             @OA\Property(property="message", type="string", example="Unread notifications retrieved successfully")
     *         )
     *     )
     * )
     */
    public function getUnreadNotifications(){
        $response = $this->repositoryObj->getUnreadNotifications();

            return $this->sendResponse($response["data"], $response["message"]);

    }

    /**
     * @OA\Post(
     *     path="/api/newNotification",
     *     tags={"Notifications"},
     *     summary="Create new notification",
     *     description="Creates a new notification",
     *     security={{"apiAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"type", "message"},
     *             @OA\Property(property="type", type="string", example="info"),
     *             @OA\Property(property="message", type="string", example="New notification message"),
     *             @OA\Property(property="user_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Notification created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="Notification created successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function store(Request $request){
        $response = $this->repositoryObj->store($request);

        if(! $response["success"]){
            return $this->sendError('Unable to Store notification.', ['error' => $response["message"]]);
        }

        return $this->sendResponse($response["data"], $response["message"]);

    }

    /**
     * @OA\Get(
     *     path="/api/showNotification/{id}",
     *     tags={"Notifications"},
     *     summary="Get notification by ID",
     *     description="Returns a specific notification",
     *     security={{"apiAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of notification to return",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="type", type="string"),
     *                 @OA\Property(property="message", type="string"),
     *                 @OA\Property(property="read_at", type="string", format="date-time", nullable=true),
     *                 @OA\Property(property="created_at", type="string", format="date-time")
     *             ),
     *             @OA\Property(property="message", type="string", example="Notification retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Notification not found"
     *     )
     * )
     */
    public function show($id){
        $response = $this->repositoryObj->show($id);

        if(! $response["success"]){
            return $this->sendError('Unable to find record.', ['error' => $response["message"]]);
        }

        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Get(
     *     path="/api/readAllNotifications/{id}",
     *     tags={"Notifications"},
     *     summary="Mark all notifications as read",
     *     description="Marks all notifications as read for a specific user",
     *     security={{"apiAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Notifications marked as read successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="All notifications marked as read")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No unread notifications found"
     *     )
     * )
     */
    public function readAllNotifications($id){
        $response = $this->repositoryObj->readAllNotifications($id);

        if(! $response["success"]){
            return $this->sendError('No New Notifications To Read.', ['error' => $response["message"]]);
        }

        return $this->sendResponse($response["data"], $response["message"]);

    }

    /**
     * @OA\Get(
     *     path="/api/readNotification/{id}",
     *     tags={"Notifications"},
     *     summary="Mark notification as read",
     *     description="Marks a specific notification as read",
     *     security={{"apiAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Notification ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Notification marked as read successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="Notification marked as read")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Notification not found"
     *     )
     * )
     */
    public function readNotification($id){
        $response = $this->repositoryObj->readNotification($id);

        if(! $response["success"]){
            return $this->sendError('Notification Not Found.', ['error' => $response["message"]]);
        }

        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Delete(
     *     path="/api/deleteNotification/{id}",
     *     tags={"Notifications"},
     *     summary="Delete notification",
     *     description="Deletes a specific notification",
     *     security={{"apiAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Notification ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Notification deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="Notification deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Notification not found"
     *     )
     * )
     */
    public function destroy($id){
        $response = $this->repositoryObj->destroy($id);

        if(! $response["success"]){
            return $this->sendError('Unable To Delete Notification', ['error' => $response["message"]]);
        }


        return $this->sendResponse($response["data"], $response["message"]);
    }
}
