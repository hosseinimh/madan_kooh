<?php

namespace App\Http\Requests\TFactor;

use App\Constants\ErrorCode;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class DeleteTFactorsRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        $response = new Response(['_result' => '0', '_error' => $validator->errors()->first(), '_errorCode' => ErrorCode::FORM_INPUT_INVALID], 200);

        throw new ValidationException($validator, $response);
    }

    public function rules()
    {
        return [
            'factor_id' => 'required|numeric|gt:0',
        ];
    }

    public function messages()
    {
        return [
            'factor_id.required' => __('tfactor.factor_id_required'),
            'factor_id.numeric' => __('tfactor.factor_id_numeric'),
            'factor_id.gt' => __('tfactor.factor_id_gt'),
        ];
    }
}
