<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ApiResponser
{
    /**
     * Build success response
     * @param $data
     * @param int $code
     * @return JsonResponse
     */
    public function successResponse($data,$message,$code = Response::HTTP_OK)
    {
        return response()->json(['data'=>$data,'code'=>$code,'message'=>$message])->header('Content-Type', 'application/json');
    }


     /**
     * Build success response
     * @param $data
     * @param int $code
     * @return JsonResponse
     */
    public function loginResponse($data,$token,$code = Response::HTTP_OK)
    {
        return response()->json(['data'=>$data,'code'=>$code,'token'=>$token])->header('Content-Type', 'application/json');
    }

    /**
     * Build error response
     * @param string|array $message
     * @param int $code
     * @return JsonResponse
     */
    public function errorResponse($messages = [], $code = 404 )
    {
        return response()->json(['error' => $messages, 'code' => $code], $code)->header('Content-Type', 'application/json');
    }
}
