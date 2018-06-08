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
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorResponse($errors = '',$data = '',$status = 400){
        return response()->json([
            'errors' => $errors,
            'data' => $data
        ],$status);
    }

    /**
     * Return an success response with info.
     *
     * @param string $data
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function successResponse($data = '',$status = 200){
        return response()->json([
            'data' => $data
        ],$status);
    }

    /**
     * Return a created response after an Add method.
     *
     * @param string $data
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function createdResponse($data = '',$status = 201){
        return response()->json([
            'data' => $data
        ],$status);
    }
}