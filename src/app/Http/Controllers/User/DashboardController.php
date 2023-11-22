<?php

namespace App\Http\Controllers\User;

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
        return $this->onOk();
    }

    public function indexWithAdmin(): HttpJsonResponse
    {
        $tfactorService = new TFactorService();
        $weightSumWB1 = $tfactorService->getSum(WeightBridge::WB_1);
        $weightSumWB1 = new TFactorSumResource($weightSumWB1);
        $weightSumWB2 = $tfactorService->getSum(WeightBridge::WB_2);
        $weightSumWB2 = new TFactorSumResource($weightSumWB2);
        return $this->onOk(['weightSumWB1' => $weightSumWB1, 'weightSumWB2' => $weightSumWB2]);
    }
}
