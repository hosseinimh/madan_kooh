<?php

namespace App\Constants;

use ReflectionClass;

abstract class WeightBridge
{
    const WB_1 = 'baskool1';
    const WB_2 = 'baskool2';

    public static function toArray()
    {
        $class = new ReflectionClass(__CLASS__);
        return $class->getConstants();
    }
}
