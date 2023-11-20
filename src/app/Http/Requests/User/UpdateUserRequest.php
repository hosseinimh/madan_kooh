<?php

namespace App\Http\Requests\User;

use App\Constants\ErrorCode;
use App\Constants\Role;
use App\Constants\Permission;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        $response = new Response(['_result' => '0', '_error' => $validator->errors()->first(), '_errorCode' => ErrorCode::UPDATE_ERROR], 200);

        throw new ValidationException($validator, $response);
    }

    public function rules()
    {
        return [
            'name' => 'required|min:3|max:50',
            'family' => 'required|min:3|max:50',
            'mobile' => 'required|digits:11',
            'roles' => 'sometimes|array',
            'roles.*' => ['sometimes', Rule::in(Role::toArray())],
            'permissions' => 'sometimes|array',
            'permissions.*' => ['sometimes', Rule::in(Permission::toArray())],
        ];
    }

    public function messages()
    {
        return [
            'username.required' => __('user.username_required'),
            'username.unique' => __('user.username_unique'),
            'username.min' => __('user.username_min'),
            'username.max' => __('user.username_max'),
            'password.required' => __('user.password_required'),
            'password.numeric' => __('user.password_numeric'),
            'password.digits' => __('user.password_digits'),
            'password.confirmed' => __('user.password_confirmed'),
            'name.required' => __('user.name_required'),
            'name.min' => __('user.name_min'),
            'name.max' => __('user.name_max'),
            'family.required' => __('user.family_required'),
            'family.min' => __('user.family_min'),
            'family.max' => __('user.family_max'),
            'mobile.required' => __('user.mobile_required'),
            'mobile.digits' => __('user.mobile_digits'),
            'roles.array' => __('user.roles_array'),
            'roles.*.in' => __('user.roles_*_in'),
            'permissions.array' => __('user.permissions_array'),
            'permissions.*.in' => __('user.permissions_*_in'),
        ];
    }
}
