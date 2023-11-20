<?php

namespace App\Http\Controllers\User;

use App\Constants\ErrorCode;
use App\Constants\Status;
use App\Facades\Notification;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\IndexUsersRequest;
use App\Http\Requests\User\LoginUserRequest as LoginRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Http\Resources\Permission\PermissionResource;
use App\Http\Resources\User\UserResource;
use App\Models\User as Model;
use App\Packages\JsonResponse;
use App\Services\PermissionService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse as HttpJsonResponse;

class UserController extends Controller
{
    public function __construct(JsonResponse $response, public UserService $service)
    {
        parent::__construct($response);
    }

    public function index(IndexUsersRequest $request): HttpJsonResponse
    {
        return $this->onItems($this->service->getPaginate($request->username, $request->name, $request->name, $request->_pn, $request->_pi));
    }

    public function show(): HttpJsonResponse
    {
        $user = new UserResource($this->service->get(auth()->user()->id));
        $permissionService = new PermissionService();
        $permissions = PermissionResource::collection($permissionService->getAll());
        return $this->onOk(['item' => $user, 'permissions' => $permissions]);
    }

    public function showWithAdmin(Model $model): HttpJsonResponse
    {
        $user = new UserResource($this->service->get($model->id));
        $permissionService = new PermissionService();
        $permissions = PermissionResource::collection($permissionService->getAll());
        return $this->onOk(['item' => $user, 'permissions' => $permissions]);
    }

    public function store(StoreUserRequest $request): HttpJsonResponse
    {
        return $this->onStore($this->service->store($request->username, $request->password, $request->name, $request->family, $request->mobile, $request->is_active, $request->roles, $request->permissions));
    }

    public function update(UpdateUserRequest $request): HttpJsonResponse
    {
        return $this->onUpdate($this->service->update(auth()->user(), $request->name, $request->family, $request->mobile));
    }

    public function updateWithAdmin(Model $model, UpdateUserRequest $request): HttpJsonResponse
    {
        return $this->onUpdate($this->service->updateSync($model, $request->name, $request->family, $request->mobile, $request->is_active, $request->roles, $request->permissions));
    }

    public function changePassword(ChangePasswordRequest $request): HttpJsonResponse
    {
        return $this->onUpdate($this->service->changePassword(auth()->user(), $request->new_password));
    }

    public function changePasswordWithAdmin(Model $model, ChangePasswordRequest $request): HttpJsonResponse
    {
        return $this->onUpdate($this->service->changePassword($model, $request->new_password));
    }

    public function login(LoginRequest $request): HttpJsonResponse
    {
        if (!auth()->attempt(['username' => $request->username, 'password' => $request->password, 'is_active' => Status::ACTIVE])) {
            return $this->onError(['_error' => __('user.user_not_found'), '_errorCode' => ErrorCode::USER_NOT_FOUND]);
        }
        Notification::onLoginSuccess(auth()->user());
        return $this->onItem($this->service->get(auth()->user()->id));
    }

    public function logout(): HttpJsonResponse
    {
        Notification::onLogout(auth()->user());
        auth()->logout();
        return $this->onOk();
    }
}
