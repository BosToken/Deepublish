<?php

namespace App\Helpers;

class SanitizeInput
{
    public static function filterArray(array $data)
    {
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data[$key] = trim(strip_tags($value));
            }
        }
        return $data;
    }
}
