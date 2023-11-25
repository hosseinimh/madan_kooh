<?php

require_once __DIR__ . '/Helper/MessageHelper.php';

return [
    'sub_category_undefined' => 'نامشخص',
    'sub_category_111' => 'ورود موفق به حساب',
    'sub_category_112' => 'ورود ناموفق به حساب',
    'sub_category_113' => 'خروج از حساب',
    'sub_category_211' => 'جستجوی فاکتورهای صادره',
    'sub_category_212' => 'حذف فاکتورهای صادره',
    'sub_category_213' => 'ویرایش فاکتور صادره',
    'sub_category_text_undefined' => 'نامشخص',
    'sub_category_text_111' => 'ورود موفق به حساب کاربری در تاریخ :field1 ساعت :field2 با دستگاه :field3',
    'sub_category_text_112' => 'ورود ناموفق به حساب کاربری :field1 در تاریخ :field2 ساعت :field3 با دستگاه :field4',
    'sub_category_text_211' => 'جستجوی فاکتورهای صادره توسط :field1',
    'sub_category_text_212' => 'حذف فاکتورهای صادره توسط :field1 از شماره قبض :field2',
    'sub_category_text_213' => 'ویرایش فاکتور صادره به شماره قبض :field1 توسط :field2',
    'category_numeric' => $numericMessage('دسته‌بندی'),
    'category_gte' => $gteNumericMessage('دسته‌بندی', 0),
];
