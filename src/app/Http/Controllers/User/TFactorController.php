<?php

namespace App\Http\Controllers\User;

use App\Exports\TFactorExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\TFactor\IndexTFactorsRequest;
use App\Http\Resources\TFactor\TFactorResource;
use App\Packages\JsonResponse;
use App\Services\TFactorService;
use Illuminate\Http\JsonResponse as HttpJsonResponse;
use Maatwebsite\Excel\Excel;

class TFactorController extends Controller
{
    public function __construct(JsonResponse $response, public TFactorService $service)
    {
        parent::__construct($response);
    }

    public function index(IndexTFactorsRequest $request): HttpJsonResponse
    {
        $items = $this->service->getPaginate($request->weight_bridge, $request->from_date, $request->to_date, $request->goods_name, $request->driver, $request->buyers_name, $request->sellers_name, $request->users, $request->factor_id, $request->factor_description1, $request->repetition_type, $request->_pn, $request->_pi);
        $itemsCount = count($items);
        $items = TFactorResource::collection($items);
        return $this->onOk(['items' => $items, 'count' => $itemsCount > 0 ? $items[0]->items_count : 0, 'prevWeightSum' => $itemsCount > 0 ? $items[0]->prev_weight_sum : 0, 'currentWeightSum' => $itemsCount > 0 ? $items[0]->current_weight_sum : 0]);
    }

    public function indexWithProps(IndexTFactorsRequest $request): HttpJsonResponse
    {
        $items = $this->service->getPaginate($request->weight_bridge, $request->from_date, $request->to_date, $request->goods_name, $request->driver, $request->buyers_name, $request->sellers_name, $request->users, $request->factor_id, $request->factor_description1, $request->repetition_type, $request->_pn, $request->_pi);
        $itemsCount = count($items);
        $items = TFactorResource::collection($items);
        $goodsName = $this->service->getAllGoodsName();
        $buyersName = $this->service->getAllBuyersName();
        $sellersName = $this->service->getAllSellersName();
        $drivers = $this->service->getAllDrivers();
        $users = $this->service->getAllUsers();
        return $this->onOk(['items' => $items, 'count' => $itemsCount > 0 ? $items[0]->items_count : 0, 'prevWeightSum' => $itemsCount > 0 ? $items[0]->prev_weight_sum : 0, 'currentWeightSum' => $itemsCount > 0 ? $items[0]->current_weight_sum : 0, 'goodsName' => $goodsName, 'buyersName' => $buyersName, 'sellersName' => $sellersName, 'drivers' => $drivers, 'users' => $users]);
    }

    public function excel(IndexTFactorsRequest $request, Excel $excel)
    {
        $tfactorExport = new TFactorExport(
            $request->weight_bridge,
            $request->from_date,
            $request->to_date,
            $request->goods_name,
            $request->driver,
            $request->buyers_name,
            $request->sellers_name,
            $request->users,
            $request->factor_id,
            $request->factor_description1,
            $request->repetition_type
        );
        return $excel->download($tfactorExport, __('tfactor.file_name') . '.xlsx');
    }

    public function print(IndexTFactorsRequest $request)
    {
        $wb = $request->weight_bridge;
        $fromDate = $request->from_date;
        $fromDate = substr($fromDate, 0, 4) . "/" . substr($fromDate, 4, 2) . "/" . substr($fromDate, 6);
        $toDate = $request->to_date;
        $toDate = substr($toDate, 0, 4) . "/" . substr($toDate, 4, 2) . "/" . substr($toDate, 6);
        $items = $this->service->getAll($request->weight_bridge, $request->from_date, $request->to_date, $request->goods_name, $request->driver, $request->buyers_name, $request->sellers_name, $request->users, $request->factor_id, $request->factor_description1, $request->repetition_type);
        $itemsCount = count($items);
        $currentWeightSum = $itemsCount > 0 ? $items[0]->current_weight_sum : 0;
        $prevWeightSum = $itemsCount > 0 ? $items[0]->prev_weight_sum : 0;
        $netWeightSum = $currentWeightSum - $prevWeightSum;
        return view('tfactors.print', compact('wb', 'fromDate', 'toDate', 'items', 'currentWeightSum', 'prevWeightSum', 'netWeightSum'));
    }
}
