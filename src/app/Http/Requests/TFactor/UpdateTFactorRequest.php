<?php

namespace App\Http\Requests\TFactor;

use App\Constants\ErrorCode;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class UpdateTFactorRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        $response = new Response(['_result' => '0', '_error' => $validator->errors()->first(), '_errorCode' => ErrorCode::UPDATE_ERROR], 200);

        throw new ValidationException($validator, $response);
    }

    public function rules()
    {
        return [
            'factor_id' => 'required|max:15',
            'factor_description1' => 'required|max:15',
        ];
    }

    public function messages()
    {
        return [
            'factor_id.required' => __('tfactor.factor_id_required'),
            'factor_id.max' => __('tfactor.factor_id_max'),
            'factor_description1.required' => __('tfactor.factor_description1_required'),
            'factor_description1.max' => __('tfactor.factor_description1_max'),
        ];
    }
}
