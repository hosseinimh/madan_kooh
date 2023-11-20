<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TFactor extends Model
{
    use HasFactory;

    protected $table = 'tbl_tfactors';

    protected $fillable = [
        'weight_bridge',
        'factor_id',
        'car_code',
        'car_number1',
        'car_number2',
        'driver',
        'current_weight',
        'prev_weight',
        'current_date',
        'current_time',
        'current_timestamp',
        'prev_date',
        'prev_time',
        'prev_timestamp',
        'goods_code',
        'goods_name',
        'unit_price',
        'buyer_code',
        'buyer_name',
        'seller_code',
        'seller_name',
        'user_id',
        'user_name',
        'user_family',
        'current_row',
        'prev_row',
        'factor_description1',
        'factor_description2',
        'factor_description3',
        'factor_description4',
        'factor_description5',
        'factor_description6',
        'factor_description7',
        'factor_description8',
        'factor_description9',
        'form_type',
        'print_location',
        'tozin_cost',
        'p_no',
        'cost_id',
        'factor_mode',
        'degree',
        'tax',
        'user_edit_id',
    ];
}
