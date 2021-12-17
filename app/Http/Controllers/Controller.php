<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function generateResponse($result, bool $success = true, $message = null, int $status_code = 200): \Illuminate\Http\JsonResponse
    {
        $response = [
            'success' => $success,
            'data' => $result,
        ];
        if ($message) {
            $response['message'] = $message;
        }
        return response()->json($response, $status_code);
    }

    public function successResponse($data, $message = null): \Illuminate\Http\JsonResponse
    {
        return $this->generateResponse($data, true, $message);
    }

    public function failedResponse($data, $message = null): \Illuminate\Http\JsonResponse
    {
        return $this->generateResponse($data, false, $message, 400);
    }
}
