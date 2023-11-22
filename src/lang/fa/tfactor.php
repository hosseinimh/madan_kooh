<?php

require_once __DIR__ . '/Helper/MessageHelper.php';

return [
    'weight_bridge_undefined' => 'نامشخص',
    'weight_bridge_1' => 'باسکول 1',
    'weight_bridge_2' => 'باسکول 2',
    'from_date_required' => $requiredMessage('از تاریخ'),
    'from_date_numeric' => $numericMessage('از تاریخ'),
    'from_date_gte' => 'مقدار فیلد از تاریخ باید برابر یا بزرگ‌تر از 1400/01/01 باشد.',
    'to_date_required' => $requiredMessage('تا تاریخ'),
    'to_date_numeric' => $numericMessage('تا تاریخ'),
    'to_date_gte' => 'مقدار فیلد تا تاریخ باید برابر یا بزرگ‌تر از 1400/01/01 باشد.',
];
