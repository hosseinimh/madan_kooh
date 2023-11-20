<?php

namespace App\Constants;

use ReflectionClass;

abstract class NotificationSubCategory
{
    const LOGIN_SUCCEED = 111;
    const LOGIN_FAILED = 112;
    const LOGOUT = 113;

    public static function toArray()
    {
        $class = new ReflectionClass(__CLASS__);
        return $class->getConstants();
    }
}
