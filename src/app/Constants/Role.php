<?php

namespace App\Constants;

use ReflectionClass;

abstract class Role
{
    const ADMIN = 'admin';

    public static function toArray()
    {
        $class = new ReflectionClass(__CLASS__);
        return $class->getConstants();
    }
}
