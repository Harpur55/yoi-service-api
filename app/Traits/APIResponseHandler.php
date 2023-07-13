<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait APIResponseHandler
{
    public function successResponse($message, $data): JsonResponse
    {
        return response()->json([
            'status'   => true,
            'message'   => $message,
            'data'      => $data,
        ], 200);
    }

    public function errorResponse($message, $data): JsonResponse
    {
        return response()->json([
            'status'   => false,
            'message'   => $message,
            'data'      => $data,
        ], 400);
    }

    public function serverErrorResponse($data): JsonResponse
    {
        return response()->json([
            'status'   => false,
            'message'   => 'server error',
            'data'      => $data,
        ], 500);
    }

    public function unAuthorizedResponse(): JsonResponse
    {
        return response()->json([
            'status'   => false,
            'message'   => 'unauthorized',
            'data'      => null,
        ], 401);
    }

    public function forbiddenResponse($message): JsonResponse
    {
        return response()->json([
            'status'    => false,
            'message'   => $message,
            'data'      => null,
        ], 403);
    }
}
