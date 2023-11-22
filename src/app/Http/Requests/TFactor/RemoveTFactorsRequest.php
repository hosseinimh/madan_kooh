<?php

namespace App\Http\Requests\TFactor;

use App\Constants\ErrorCodes;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class RemoveTFactorsRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        $response = new Response(['_result' => '0', '_error' => $validator->errors()->first(), '_errorCode' => ErrorCodes::FORM_INPUT_INVALID], 200);

        throw new ValidationException($validator, $response);
    }

    public function rules()
    {
        return [
            'id' => 'required|numeric|gt:0',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => __('tfactor.id_required'),
            'id.numeric' => __('tfactor.id_numeric'),
            'id.gt' => __('tfactor.id_gt'),
        ];
    }
}