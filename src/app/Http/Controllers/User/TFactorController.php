<?php

namespace App\Http\Controllers\User;

use App\Constants\WeightBridge;
use App\Exports\TFactorExport;
use App\Facades\Helper;
use App\Facades\Notification;
use App\Http\Controllers\Controller;
use App\Http\Requests\TFactor\DeleteTFactorsRequest;
use App\Http\Requests\TFactor\IndexTFactorsRequest;
use App\Http\Requests\TFactor\UpdateTFactorRequest;
use App\Http\Resources\TFactor\TFactorResource;
use App\Packages\JsonResponse;
use App\Services\TFactorService;
use Exception;
use Illuminate\Http\JsonResponse as HttpJsonResponse;
use Illuminate\Http\Request;
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
        Notification::onSearchTFactors(auth()->user());
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
        Notification::onSearchTFactors(auth()->user());
        return $this->onOk(['items' => $items, 'count' => $itemsCount > 0 ? $items[0]->items_count : 0, 'prevWeightSum' => $itemsCount > 0 ? $items[0]->prev_weight_sum : 0, 'currentWeightSum' => $itemsCount > 0 ? $items[0]->current_weight_sum : 0, 'goodsName' => $goodsName, 'buyersName' => $buyersName, 'sellersName' => $sellersName, 'drivers' => $drivers, 'users' => $users]);
    }

    public function getLastFactorId(Request $request)
    {
        try {
            $weightBridge = ($request->wb === WeightBridge::WB_1 || $request->wb === WeightBridge::WB_2) ? $request->wb : null;
            if ($weightBridge) {
                $item = $this->service->getLast($weightBridge);
                if ($item) {
                    $last_id = $item['factor_id'] ?? 0;
                } else {
                    $last_id = 0;
                }
                $data = ['weight_bridge' => $weightBridge, 'last_id' => $last_id];
                return $this->onOk($data);
            }
        } catch (Exception) {
        }
        $data = ['weight_bridge' => '', 'last_id' => -1];
        return $this->onError($data);
    }

    public function store(Request $request)
    {
        try {
            $json = json_decode($request->records);
            if (!$json) {
                $data = ['store' => 'ERROR'];
                return $this->onError($data);
            }
            $records = array_values($json);
            foreach ($records as $record) {
                try {
                    if ($record->FactorId && intval($record->FactorId) > 0) {
                        $weightBridge = ($record->weight_bridge === WeightBridge::WB_1 || $record->weight_bridge === WeightBridge::WB_2) ? $record->weight_bridge : null;
                        if ($weightBridge) {
                            $item = $this->service->getByFactorId($record->FactorId, $weightBridge);
                            if (!$item) {
                                $this->service->store(
                                    $weightBridge,
                                    $record->FactorId,
                                    $record->CarCode ?? '',
                                    $record->CarNumber1 ?? '',
                                    $record->CarNumber2 ?? '',
                                    $record->Driver ?? '',
                                    $record->CurrentWaghit ?? 0,
                                    $record->PrevWaghit ?? 0,
                                    $record->CurrentDate ?? '',
                                    $record->CurrentTime ?? '',
                                    $record->PrevDate ?? '',
                                    $record->PrevTime ?? '',
                                    $record->GoodsCode ?? 0,
                                    $record->GoodsName ?? '',
                                    $record->Unit_Price ?? 0,
                                    $record->PurchCode ?? 0,
                                    $record->PurchName ?? '',
                                    $record->SalerCode ?? 0,
                                    $record->SalerName ?? '',
                                    $record->UserId ?? 0,
                                    $record->UserName ?? '',
                                    $record->UserFamily ?? '',
                                    $record->CurrentRow ?? 0,
                                    $record->PrevRow ?? 0,
                                    $record->FactorDesc1 ?? '',
                                    $record->FactorDesc2 ?? '',
                                    $record->FactorDesc3 ?? '',
                                    $record->FactorDesc4 ?? '',
                                    $record->FactorDesc5 ?? '',
                                    $record->FactorDesc6 ?? '',
                                    $record->FactorDesc7 ?? '',
                                    $record->FactorDesc8 ?? '',
                                    $record->FactorDesc9 ?? '',
                                    $record->FormType ?? 0,
                                    $record->PrintLocation ?? 0,
                                    $record->TozinCost ?? 0,
                                    $record->PNo ?? 0,
                                    $record->CostId ?? 0,
                                    $record->FactorMod ?? 0,
                                    $record->Digree ?? '',
                                    $record->Maliat ?? 0,
                                    $record->UserEditId ?? 0,
                                );
                            }
                        }
                    }
                } catch (Exception) {
                }
            }
            $data = ['store' => 'OK'];
            return $this->onOk($data);
        } catch (Exception $e) {
            Helper::logError($e);
        }
        $data = ['store' => 'ERROR'];
        return $this->onError($data);
    }

    public function update(UpdateTFactorRequest $request): HttpJsonResponse
    {
        $result = $this->service->update($request->factor_id, $request->factor_description1);
        if ($result) {
            Notification::onEditTFactor(auth()->user(), $request->factor_id);
        }
        return $this->onUpdate($result);
    }

    public function deleteTFactors(DeleteTFactorsRequest $request)
    {
        $result = $this->service->deleteTFactors($request->factor_id);
        if ($result) {
            Notification::onDeleteTFactors(auth()->user(), $request->factor_id);
        }
        return $this->onDelete($result);
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
