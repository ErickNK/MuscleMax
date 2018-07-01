<?php


namespace App\Traits;


trait DoesResponses
{
    /**
     * Return an error response
     *
     * @param string $errors
     * @param string $data
     * @param int $status
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorResponse($errors = '',$data = '',$status = 400,$message = 'failure'){
        return response()->json([
            'message' => $message,
            'errors' => $errors,
            'data' => $data
        ],$status);
    }

    /**
     * Return an success response with info.
     *
     * @param string $data
     * @param int $status
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function successResponse($data = '',$status = 200,$message = 'success'){
        return response()->json([
            'message' => $message,
            'data' => $data
        ],$status);
    }
}