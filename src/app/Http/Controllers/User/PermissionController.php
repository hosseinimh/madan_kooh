<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Packages\JsonResponse;
use App\Services\PermissionService;
use Illuminate\Http\JsonResponse as HttpJsonResponse;

class PermissionController extends Controller
{
    public function __construct(JsonResponse $response, public PermissionService $service)
    {
        parent::__construct($response);
    }

    public function index(): HttpJsonResponse
    {
        return $this->onItems($this->service->getAll());
    }
}
