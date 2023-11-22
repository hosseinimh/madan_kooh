<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\TFactor\IndexTFactorsRequest;
use App\Http\Resources\TFactor\TFactorResource;
use App\Packages\JsonResponse;
use App\Services\TFactorService;
use Illuminate\Http\JsonResponse as HttpJsonResponse;

class TFactorController extends Controller
{
    public function __construct(JsonResponse $response, public TFactorService $service)
    {
        parent::__construct($response);
    }

    public function index(IndexTFactorsRequest $request): HttpJsonResponse
    {
        $items = $this->service->getPaginate($request->weight_bridge, $request->from_date, $request->to_date, $request->goods_name, $request->driver, $request->buyers_name, $request->sellers_name, $request->users, $request->factor_id, $request->repetition_type, $request->_pn, $request->_pi);
        $items = TFactorResource::collection($items);
        return $this->onItems($items);
    }

    public function indexWithProps(IndexTFactorsRequest $request): HttpJsonResponse
    {
        $items = $this->service->getPaginate($request->weight_bridge, $request->from_date, $request->to_date, $request->goods_name, $request->driver, $request->buyers_name, $request->sellers_name, $request->users, $request->factor_id, $request->repetition_type, $request->_pn, $request->_pi);
        $items = TFactorResource::collection($items);
        $goodsName = $this->service->getAllGoodsName();
        $buyersName = $this->service->getAllBuyersName();
        $sellersName = $this->service->getAllSellersName();
        $drivers = $this->service->getAllDrivers();
        $users = $this->service->getAllUsers();
        return $this->onOk(['items' => $items, 'count' => count($items) > 0 ? $items[0]->items_count : 0, 'goodsName' => $goodsName, 'buyersName' => $buyersName, 'sellersName' => $sellersName, 'drivers' => $drivers, 'users' => $users]);
    }
}
