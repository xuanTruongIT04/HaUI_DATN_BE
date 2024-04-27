<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function sendSuccessResponse($data)
    {
        return response()->json([
            'status' => true,
            'data' => $data
        ]);

    }
    public function sendErrorResponse($errorMessage)
    {
        return response()->json([
            'status' => false,
            'errors' => $errorMessage
        ], JsonResponse::HTTP_CONFLICT);
    }
}