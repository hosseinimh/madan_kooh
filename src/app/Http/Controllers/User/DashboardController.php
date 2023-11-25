<?php

namespace App\Http\Controllers\User;

use App\Constants\Permission;
use App\Constants\Role;
use App\Constants\WeightBridge;
use App\Http\Controllers\Controller;
use App\Http\Resources\TFactor\TFactorSumResource;
use App\Packages\JsonResponse;
use App\Services\TFactorService;
use Illuminate\Http\JsonResponse as HttpJsonResponse;

class DashboardController extends Controller
{
    public function __construct(JsonResponse $response)
    {
        parent::__construct($response);
    }

    public function index(): HttpJsonResponse
    {
        $tfactorService = new TFactorService();
        $roles = auth()->user()->getRoleNames()->toArray();
        $permissions = auth()->user()->getPermissionNames()->toArray();
        $wb1 = null;
        $wb2 = null;
        if (in_array(Role::ADMIN, $roles) || in_array(Permission::READ_ALL_WBS, $permissions) || in_array(Permission::READ_WB_1, $permissions)) {
            $wb1 = $tfactorService->getSum(WeightBridge::WB_1);
            $wb1 = new TFactorSumResource($wb1);
        }
        if (in_array(Role::ADMIN, $roles) || in_array(Permission::READ_ALL_WBS, $permissions) || in_array(Permission::READ_WB_2, $permissions)) {
            $wb2 = $tfactorService->getSum(WeightBridge::WB_2);
            $wb2 = new TFactorSumResource($wb2);
        }
        return $this->onOk(['wb1' => $wb1, 'wb2' => $wb2]);
    }
}
