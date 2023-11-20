<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
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
        return $this->onOk();
    }
}
