<?php

namespace App\Http\Requests\TFactor;

use App\Constants\ErrorCode;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class IndexTFactorsRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        $response = new Response(['_result' => '0', '_error' => $validator->errors()->first(), '_errorCode' => ErrorCode::FORM_INPUT_INVALID], 200);

        throw new ValidationException($validator, $response);
    }

    public function rules()
    {
        return [
            'from_date' => 'required|numeric|gte:14000101',
            'to_date' => 'required|numeric|gte:14000101',
        ];
    }

    public function messages()
    {
        return [
            'from_date.required' => __('tfactor.from_date_required'),
            'from_date.numeric' => __('tfactor.from_date_numeric'),
            'from_date.gte' => __('tfactor.from_date_gte'),
            'to_date.required' => __('tfactor.to_date_required'),
            'to_date.numeric' => __('tfactor.to_date_numeric'),
            'to_date.gte' => __('tfactor.to_date_gte'),
        ];
    }
}
