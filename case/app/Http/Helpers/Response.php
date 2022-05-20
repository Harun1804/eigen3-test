<?php

namespace App\Http\Helpers;

class Response
{
    public static function success($data = [], $message = 'Success')
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ]);
    }

    public static function error($message = 'Error')
    {
        return response()->json([
            'status' => 'error',
            'message' => $message
        ]);
    }
}
