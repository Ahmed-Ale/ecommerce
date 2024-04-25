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
}
