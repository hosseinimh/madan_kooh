<?php

namespace App\Constants;

use ReflectionClass;

abstract class Permission
{
    const READ_WB_1 = 'read_wb_1';
    const READ_WB_2 = 'read_wb_2';
    const READ_ALL_WBS = 'read_all_wbs';
    const EDIT_FACTOR_DESCRIPTION = 'edit_factor_description';

    public static function toArray()
    {
        $class = new ReflectionClass(__CLASS__);
        return $class->getConstants();
    }
}
