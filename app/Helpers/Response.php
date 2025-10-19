<?php

namespace App\Helpers;

class Response
{
    public static function success($data = [], $message = "Success", $code = 200)
    {
        return response()->json($data);
    }
    public static function error($data = [], $message = "Error", $code = 400)
    {
        return response()->json($data);
    }
}