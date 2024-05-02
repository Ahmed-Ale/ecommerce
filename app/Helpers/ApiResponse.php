<?php

namespace App\Helpers;

class ApiResponse
{
    public static function response($status = 200, $message = 'Success', $data = [])
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $status);
    }
    public static function not_found($something = 'data')
    {
        return response()->json([
            'status' => 404,
            'message' => $something . ' Not Found',
            'data' => [],
        ], 404);
    }
}
