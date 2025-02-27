<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repository\RolesPermissions\PermissionGroupManagement\PermissionGroupManagementInterface;

class PermissionGroupController extends BaseController
{
    public $repositoryObj;

    public function __construct(PermissionGroupManagementInterface $groupObj){
        $this->repositoryObj = $groupObj;
    }

    public function index(Request $request){
        $response = $this->repositoryObj->index($request);

        if(! $response["success"]){
            return $this->sendError('No group found', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    public function store(Request $request){
        $response = $this->repositoryObj->store($request);

        if(! $response["success"]){
            return $this->sendError('Unable to store group', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    public function update($id, Request $request){
        $response = $this->repositoryObj->update($id, $request);

        if(! $response["success"]){
            return $this->sendError('Unable to update group', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    public function destroy($id){
        $response = $this->repositoryObj->destroy($id);

        if(! $response["success"]){
            return $this->sendError('Unable to delete group', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }
}
