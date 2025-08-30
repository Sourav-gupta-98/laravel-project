<?php

namespace App\Constants;
class AppConstant
{

    public static function payment_status(): array
    {
        return ['COD', 'ONLINE'];
    }

    public static function order_status(): array
    {
        return ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded'];
    }
}
