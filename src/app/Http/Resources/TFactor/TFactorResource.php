<?php

namespace App\Http\Resources\TFactor;

use App\Facades\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class TFactorResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'weightBridge' => $this->weight_bridge,
            'weightBridgeText' => Helper::getWeightBridgeText($this->weight_bridge),
            'factorId' => $this->factor_id,
            'carCode' => $this->car_code,
            'carNumber1' => $this->car_number1,
            'carNumber2' => $this->car_number2,
            'driver' => $this->driver,
            'currentWeight' => $this->current_weight,
            'prevWeight' => $this->prev_weight,
            'currentDate' => $this->current_date,
            'prevDate' => $this->prev_date,
            'currentTime' => $this->current_time,
            'prevTime' => $this->prev_time,
            'goodsCode' => $this->goods_code,
            'goodsName' => $this->goods_name,
            'unitPrice' => $this->unit_price,
            'buyerCode' => $this->buyer_code,
            'buyerName' => $this->buyer_name,
            'sellerCode' => $this->seller_code,
            'sellerName' => $this->seller_name,
            'userId' => $this->user_id,
            'userName' => $this->user_name,
            'userFamily' => $this->user_family,
            'currentRow' => $this->current_row,
            'prevRow' => $this->prev_row,
            'factorDescription1' => $this->factor_description1,
            'factorDescription2' => $this->factor_description2,
            'factorDescription3' => $this->factor_description3,
            'factorDescription4' => $this->factor_description4,
            'factorDescription5' => $this->factor_description5,
            'factorDescription6' => $this->factor_description6,
            'factorDescription7' => $this->factor_description7,
            'factorDescription8' => $this->factor_description8,
            'factorDescription9' => $this->factor_description9,
            'formType' => $this->form_type,
            'printLocation' => $this->print_location,
            'tozinCost' => $this->tozin_cost,
            'pNo' => $this->p_no,
            'costId' => $this->cost_id,
            'factorMode' => $this->factor_mode,
            'degree' => $this->degree,
            'tax' => $this->tax,
            'userEditId' => $this->user_edit_id,
        ];
    }
}
