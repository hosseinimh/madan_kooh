<?php

namespace App\Constants;

use ReflectionClass;

abstract class TFactorRepitionType
{
    const ALL = 'all';
    const LAST = 'last';
    const REPETITION = 'repetition';

    public static function toArray()
    {
        $class = new ReflectionClass(__CLASS__);
        return $class->getConstants();
    }
}
