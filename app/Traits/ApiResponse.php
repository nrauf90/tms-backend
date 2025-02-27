<?php

namespace App\Traits;

trait ApiResponse {

    /**
     * successResponse
     *
     * @param  mixed $data
     * @param  mixed $statusCode
     * @param  mixed $code
     * @param  mixed $message
     * @param  mixed $totalCount
	 * Description: Function used to handle the api success response.
     * @return void
     */

    protected function successResponse($data,$statusCode, $code = 200,$message = null,$totalCount=-1)
	{
		return response()->json([
            'statusCode'=>$statusCode,
			'message' => $message, 
			'payload' => (object) $data,
			'error'=> (object)[],
            'totalCount'=>$totalCount,
            'isSuccess'=>true,
		], $code);
	}

    /**
	 * errorResponse
	 *
	 * @param  mixed $statusCode
	 * @param  mixed $code
	 * @param  mixed $errorDescription
	 * @param  mixed $message
	 * @param  mixed $errors
	 * @return void
	 * Description: Function used to handle the api failure response.
	 */
    protected function errorResponse($statusCode,$code,$message = null,$errors=array())
	{
		return response()->json([
            'statusCode'=>$statusCode,
			'message' => $message, 
			'payload' => (object)[],
            'error'=> (object) $errors,
            'totalCount'=>-1,
            'isSuccess'=>false,
		], $code);
	}
}