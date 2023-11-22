<?php

namespace App\Http\Requests\TFactor;

use App\Constants\ErrorCode;
use App\Constants\Permission;
use App\Constants\Role;
use App\Constants\WeightBridge;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Closure;

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
            'weight_bridge' => [function (string $attribute, mixed $value, Closure $fail) {
                $roles = auth()->user()->getRoleNames()->toArray();
                $permissions = auth()->user()->getPermissionNames()->toArray();
                switch ($value) {
                    case WeightBridge::WB_1:
                        if (!in_array(Role::ADMIN, $roles) && !in_array(Permission::READ_WB_1, $permissions)) {
                            $fail(__('user.not_authorized'));
                        }
                        break;
                    case WeightBridge::WB_2:
                        if (!in_array(Role::ADMIN, $roles) && !in_array(Permission::READ_WB_2, $permissions)) {
                            $fail(__('user.not_authorized'));
                        }
                        break;
                    default:
                        if (!in_array(Role::ADMIN, $roles)) {
                            $fail(__('user.not_authorized'));
                        }
                        break;
                }
            }],
            'from_date' => 'required|numeric|gte:14000101|lte:14201230',
            'to_date' => 'required|numeric|gte:14000101|lte:14201230',
            'factor_id' => 'sometimes|max:15',
            'factor_description1' => 'sometimes|max:15',
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
            'factor_id.max' => __('tfactor.factor_id_max'),
            'factor_description1.max' => __('tfactor.factor_description1_max'),
        ];
    }
}
