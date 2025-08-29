<?php

namespace App\Services;
class UtilityService
{

    public static function generateUniqueCode()
    {
        return md5(uniqid(date('Y-m-d H:i:s') . random_int(100000, 999999), true));
    }
}
