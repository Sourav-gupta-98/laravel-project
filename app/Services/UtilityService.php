<?php

namespace App\Services;
class UtilityService
{

    public static function generateUniqueCode()
    {
        return md5(uniqid(date('Y-m-d H:i:s') . random_int(100000, 999999), true));
    }

    public static function trimKeys($dataArr, $exceptKeysArr)
    {
        if ($dataArr && count($dataArr) > 0 && $exceptKeysArr && count($exceptKeysArr) > 0) {
            $finalArr = [];
            foreach ($exceptKeysArr as $except) {
                if (array_key_exists($except, $dataArr)) {
                    $finalArr[$except] = $dataArr[$except];
                }
            }
            return $finalArr;
        } else {
            return [];
        }
    }

    public static function trimBlankKeys($keysArr)
    {
        if ($keysArr && count($keysArr) > 0) {
            $finalArr = [];
            foreach ($keysArr as $keys => $value) {
                if ($value && $value !== '') {
                    $finalArr[$keys] = $value;
                }
            }
            return $finalArr;
        } else {
            return [];
        }
    }
}
