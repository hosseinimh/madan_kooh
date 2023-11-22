<?php

namespace App\Http\Resources\TFactor;

use Illuminate\Http\Resources\Json\JsonResource;

class TFactorSumResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'prevWeightSum' => intval($this->prev_weight_sum),
            'currentWeightSum' => intval($this->current_weight_sum),
            'itemsCount' => $this->prev_weight_sum || $this->current_weight_sum ? $this->items_count : 0,
        ];
    }
}
