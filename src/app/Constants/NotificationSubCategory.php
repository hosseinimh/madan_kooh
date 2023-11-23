<?php

namespace App\Constants;

use ReflectionClass;

abstract class NotificationSubCategory
{
    const LOGIN_SUCCEED = 111;
    const LOGIN_FAILED = 112;
    const LOGOUT = 113;
    const SEARCH_TFACTORS = 211;
    const DELETE_TFACTORS = 212;

    public static function toArray()
    {
        $class = new ReflectionClass(__CLASS__);
        return $class->getConstants();
    }
}
